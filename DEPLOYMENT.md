# Deploying TheSPARK

A checklist for putting the site on a real server. Local development needs none of this.

## 1. Server requirements

- PHP 8.2 or newer (the site is tested on 8.4) with `pdo_mysql` or `pdo_pgsql`, `mbstring`, `xml`, `fileinfo`, `gd`
- MySQL 8+ / MariaDB 10.5+, or PostgreSQL 15+ (for example Supabase). On MySQL search uses a full-text index; on PostgreSQL it uses plain word matching, which is fine for a campus-sized archive
- PHP upload limits of at least 40 MB (`upload_max_filesize` and `post_max_size`), because issue PDFs may be 35 MB
- Node 18+ to build the frontend, Composer 2
- A way to run a command every minute (cron)

## 2. First-time setup

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
cp .env.example .env          # then fill it in (section 3)
php artisan key:generate      # a NEW key: never reuse the one from development
php artisan migrate --force   # also creates the tables that hold uploaded photos and PDFs (section 6)
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Point the web server's document root at `public/`, never at the project folder.

## 3. `.env` for production

| Setting | Value |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` (debug pages print passwords and file paths) |
| `APP_URL` | the real address, with `https://`. Newsletter and password emails build their links from it |
| `DB_*` | a database user that can only reach this database, not `root` |
| `MAIL_*` | a mail account's **app password**. Gmail allows roughly 500 emails a day; move to a mail service if the newsletter list grows past that |
| `SANCTUM_TOKEN_EXPIRATION` | minutes before people must sign in again (default 10080 = 7 days) |
| `CORS_ALLOWED_ORIGINS` | leave empty unless another website must call the API |
| `CSP_MODE` | leave empty: pages enforce a Content-Security-Policy on a live site (see section 5) |
| `CACHE_LIMITER_STORE` | `file`. The request-limit counters are touched by every request; on the database they cost 5 to 9 extra queries each time, on a remote database that is most of the response time. The folder `storage/framework/cache/data` must be writable |

The app trusts the `X-Forwarded-*` headers of whatever sits in front of it (Render's load balancer, Cloudflare), so visitors keep their own address for request limits, sign-in lockouts and unique-visitor counts, and links are built with `https`. Only run it behind such a proxy, never directly on the internet with these headers open to anyone.

Never commit `.env`. It is in `.gitignore`.

## 4. The scheduler (required)

Add one cron entry:

```
* * * * * cd /path/to/site && php artisan schedule:run >> /dev/null 2>&1
```

It runs:

| Job | When | What it does |
|---|---|---|
| `articles:publish-scheduled` | every minute | publishes articles whose scheduled time has come |
| `newsletter:digest` | Mondays 08:00 | emails subscribers the week's articles |
| `maintenance:prune` | daily 03:00 | deletes old page views, expired codes, old read notifications |
| `sanctum:prune-expired` | daily | deletes expired sign-in tokens |

Without cron, scheduled articles only go live when someone happens to load a page.

## 5. HTTPS and browser protections

Serve the site over HTTPS only. The app then sends `Strict-Transport-Security` on its own, and builds every link with `https`. Uploaded files and sign-in tokens should never travel over plain HTTP.

Pages also carry a **Content-Security-Policy** that only lets scripts, frames and connections come from the site itself plus the few outside services it uses (Google Fonts, the PDF viewer's CDN, YouTube). While developing it only *reports* problems: open the site with the browser's developer console open and look for "Content Security Policy" messages. If the console is clean, a live site can enforce it. If you add a new outside service (another font, embed or script), add it to `app/Http/Middleware/SecurityHeaders.php` first.

The web server must never run PHP from `storage/` (uploads). On Apache/Nginx, only `public/index.php` should be executable.

## 6. Uploaded files and backups

Uploads (article photos, gallery, issue PDFs, avatars) are stored **in the database**, in the tables `stored_files` (one row per file) and `stored_file_chunks` (the bytes, in 256 KB pieces). The site serves them itself at `/storage/{path}`, with a cache lifetime of a year, since every upload gets a new name. Nothing is written to the server's disk, so it does not matter that a host such as Render wipes its disk on every deploy, and several servers can run at once. That means:

- **The database backup is the only backup**, and it includes every photo and PDF.
- The database grows by the size of the uploads (about 27 MB for the site's current files). Check your database plan's size limit.
- `php artisan storage:link` is not needed any more.
- Large photos are scaled down when they are uploaded, to save database space and load time: profile pictures to 512 px on the longest side, article and gallery photos to 2400 px (`App\Support\Images`). Smaller pictures, GIFs (they may be animated) and anything that cannot be processed safely are stored exactly as uploaded. This needs PHP's `gd` (with JPEG, PNG and WebP) and `exif` extensions; without them uploads still work, just unscaled.

Files that were uploaded before this change (still in `storage/app/public`) are copied in with `php artisan uploads:import` (add `--dry-run` to preview). It only imports files that a row actually uses, verifies each copy by size and checksum, and can be run again safely.

Database backup, for example nightly:

```bash
mysqldump --single-transaction -u USER -p DATABASE | gzip > backup-$(date +%F).sql.gz     # MySQL / MariaDB
pg_dump "$DB_URL" | gzip > backup-$(date +%F).sql.gz                                        # PostgreSQL / Supabase
```

Keep copies off the server, and test a restore once.

## 7. After every update

Take a database backup first (section 6). Some updates remove unused columns, which cannot be undone without one.

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## 8. Speed: caching and the CDN

**What the app already does**

- The public reader lists are cached for 60 seconds on the server (`CACHE_STORE` in `.env`) and are rebuilt at once when an article, video, photo, issue or section is saved or deleted. They are also sent with `Cache-Control: public` and an `ETag`, so browsers and a CDN can keep them for 30 to 60 seconds. Everything else in `/api` stays `no-store`.
- The built CSS/JS is split per page; the file names change whenever their content does.

**Have the web server cache the built files.** Everything in `public/build/assets/` has a hash in its name, so it can be kept forever. Turn on gzip or brotli too. Nginx:

```nginx
location /build/assets/ { add_header Cache-Control "public, max-age=31536000, immutable"; }
gzip on;
gzip_types text/css application/javascript application/json image/svg+xml;
```

Apache (`mod_headers` and `mod_deflate`): `<LocationMatch "^/build/assets/"> Header set Cache-Control "public, max-age=31536000, immutable" </LocationMatch>`

Also give `/assets/` (logos, images) a cache lifetime of a few days. Uploads (`/storage/`) already come with a one-year cache header from the app itself.

**Adding a CDN (optional).** Point a CDN (Cloudflare, Bunny, CloudFront...) at the site as its origin, then:

1. Set `ASSET_URL=https://cdn.example.com` in `.env`.
2. Run `npm run build` (the address is baked into the built files), then `php artisan optimize:clear && php artisan config:cache`.
3. Make sure the CDN sends `Access-Control-Allow-Origin: *` for files under `/build/` (scripts loaded from another address need it).

The Content-Security-Policy allows the `ASSET_URL` address automatically. Uploaded photos and PDFs (`/storage/`) still come from the site itself; a CDN in front of the whole domain covers those as well.

## 9. Health check

`/up` answers `200` when the app is running. Point an uptime monitor at it.

## 10. Hosting on Render with a Supabase database

Render runs the site as a Docker web service, and Supabase provides the PostgreSQL database. Because uploads live in the database (section 6), nothing else is needed for files.

**A. Supabase (database)**

1. Create a project. In *Project Settings > Database > Connection string*, copy the **Session pooler** string (it works over IPv4, which the direct connection does not). It looks like `postgresql://postgres.PROJECTREF:PASSWORD@aws-0-REGION.pooler.supabase.com:5432/postgres`. If the password has special characters (`@ : / # ?`), percent-encode them.
2. On your own computer, enable PostgreSQL in PHP: in `php.ini` remove the `;` in front of `extension=pdo_pgsql` and `extension=pgsql`.
3. Create the tables in Supabase (PowerShell shown; in bash use `export`):

```powershell
$env:DB_CONNECTION = "pgsql"; $env:DB_URL = "postgresql://postgres.PROJECTREF:PASSWORD@aws-0-REGION.pooler.supabase.com:5432/postgres"
php artisan migrate --force
Remove-Item Env:DB_CONNECTION, Env:DB_URL      # back to the local MySQL
```

4. Copy the existing data, including every uploaded photo and PDF, from the local database into Supabase:

```bash
php artisan uploads:import                      # only if some uploads are still just files in storage/app/public
php artisan data:copy "postgresql://postgres.PROJECTREF:PASSWORD@aws-0-REGION.pooler.supabase.com:5432/postgres"
```

It copies table by table in the right order, resets the id counters and finishes by comparing the row counts. It refuses to run if the target already holds data (use `--force` to replace it).

**B. Render (from the Blueprint)**

The repository contains everything Render needs: a `Dockerfile` (Render has no PHP runtime; it builds this) and `render.yaml` (the Blueprint that describes the services). Nothing has to be created by hand.

1. Push the repository to GitHub.
2. In Render: *New > Blueprint*, choose the repository. Render reads `render.yaml`, shows the services it will create and their cost, and asks for the secret values:
   - `APP_KEY`: run `php artisan key:generate --show` on your computer (it starts with `base64:`). Render cannot generate this one, because Laravel needs that exact format.
   - `APP_URL`: the site's address with `https://` (the `onrender.com` address Render gives it, or your own domain).
   - `DB_URL`: the Supabase Session pooler string from step A.
   - `MAIL_USERNAME`, `MAIL_PASSWORD` (an app password), `MAIL_FROM_ADDRESS`.
3. Before applying, edit `region` in `render.yaml` to the one nearest your Supabase project (it cannot be changed afterwards). The web service starts on the `free` plan: change `plan` to a paid size when the site should stay awake.
4. On every start the container creates or updates the database tables (`AUTORUN_ENABLED`), caches the configuration and routes, and serves the site on port 8080. Watch the first deploy in the *Logs* tab.
5. Upload limits: the image accepts 100 MB by default, above the 35 MB PDF limit, so nothing to set. Render has no 4.5 MB request cap.

The image is built and configured to these documents: [image variables](https://serversideup.net/open-source/docker-php/docs/reference/environment-variable-specification) and [Render's Blueprint spec](https://render.com/docs/blueprint-spec). The build steps were rehearsed outside Docker (clean `npm ci` and build, `composer install --no-dev`, `artisan optimize`, a production boot), but the image itself has not been built yet, so the first deploy is the real test.

**C. The scheduler**

Render has no cron daemon inside a web service, so `render.yaml` adds a second service, `sparky-scheduler`: the same image running `php artisan schedule:work`. It is a paid service type (workers cannot be free), and it asks for the same secrets again. Delete that block from `render.yaml` to go without it: scheduled articles then only go live when someone loads a page, and the weekly newsletter is not sent.

**D. Things to know**

- Free Render web services go to sleep after about 15 minutes without visitors, and the first visit afterwards is slow. Free Supabase projects are paused after a week of inactivity and hold 500 MB, which includes the uploads.
- Every photo is read from the database when it is first requested, then cached by the browser for a year. A CDN in front of the site (Cloudflare's free plan, for instance) keeps that load off the database.
- Search on PostgreSQL uses plain word matching (case-insensitive) instead of the MySQL full-text index. The results are the same for a campus-sized archive; it only gets slower on hundreds of thousands of articles.

## 11. Running the tests (development only)

```bash
composer test
```

Tests use their own database, `sparky_test` (MySQL). They refuse to run against any database whose name does not end in `_test`, so they can never touch real data. Create it once:

```sql
CREATE DATABASE sparky_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
