# Deploying TheSPARK

A checklist for putting the site on a real server. Local development needs none of this.

## 1. Server requirements

- PHP 8.2 or newer (the site is tested on 8.4) with `pdo_mysql`, `mbstring`, `xml`, `fileinfo`, `gd`
- MySQL 8+ or MariaDB 10.5+ (search uses a full-text index)
- Node 18+ to build the frontend, Composer 2
- A way to run a command every minute (cron)

## 2. First-time setup

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
cp .env.example .env          # then fill it in (section 3)
php artisan key:generate      # a NEW key: never reuse the one from development
php artisan migrate --force
php artisan storage:link      # makes uploaded photos, PDFs and avatars reachable
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

Uploads (article photos, gallery, issue PDFs, avatars) are stored on the server's own disk under `storage/app/public`. That means:

- **Back them up** together with the database. Losing the disk loses every photo.
- The site can't run on several servers at once without a shared disk. If that is ever needed, uploads must move to object storage (S3 or similar), which is a code change, not just a setting.

Database backup, for example nightly:

```bash
mysqldump --single-transaction -u USER -p DATABASE | gzip > backup-$(date +%F).sql.gz
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

Also give `/assets/` (logos, images) and `/storage/` (uploads) a cache lifetime of a few days.

**Adding a CDN (optional).** Point a CDN (Cloudflare, Bunny, CloudFront...) at the site as its origin, then:

1. Set `ASSET_URL=https://cdn.example.com` in `.env`.
2. Run `npm run build` (the address is baked into the built files), then `php artisan optimize:clear && php artisan config:cache`.
3. Make sure the CDN sends `Access-Control-Allow-Origin: *` for files under `/build/` (scripts loaded from another address need it).

The Content-Security-Policy allows the `ASSET_URL` address automatically. Uploaded photos and PDFs (`/storage/`) still come from the site itself; a CDN in front of the whole domain covers those as well.

## 9. Health check

`/up` answers `200` when the app is running. Point an uptime monitor at it.

## 10. Running the tests (development only)

```bash
composer test
```

Tests use their own database, `sparky_test` (MySQL). They refuse to run against any database whose name does not end in `_test`, so they can never touch real data. Create it once:

```sql
CREATE DATABASE sparky_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
