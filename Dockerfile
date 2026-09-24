# syntax=docker/dockerfile:1
#
# TheSPARK for Render (or any Docker host): nginx + PHP-FPM on port 8080, the built frontend, no dev tools.
# Render has no PHP runtime, so it builds this file (see render.yaml).

# ---- 1. Build the frontend (Vite) -------------------------------------------------------------------
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
RUN npm run build


# ---- 2. The site ---------------------------------------------------------------------------------------
# serversideup/php: nginx + PHP-FPM as an unprivileged user, PHP settings through environment variables, and
# optional start-up automations (migrate, cache config and routes) switched on with AUTORUN_ENABLED.
FROM serversideup/php:8.4-fpm-nginx

# gd: scaling uploaded photos down (needs JPEG, PNG and WebP: the installer builds it with all three)
# exif: reading a photo's rotation so a scaled phone photo is not turned on its side
# pdo_pgsql: the Supabase database (already in the image; listed so the build fails loudly if it ever is not)
USER root
RUN install-php-extensions gd exif pdo_pgsql
USER www-data

WORKDIR /var/www/html

# Install the PHP packages first, so this step is cached until composer.json / composer.lock change
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction --prefer-dist

# The application, then the frontend built in stage 1 (see .dockerignore for what is left out)
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

RUN composer dump-autoload --no-dev --no-scripts --optimize --classmap-authoritative \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

# The image's own start-up (nginx + php-fpm) is left as it is. Settings come from the environment
# (render.yaml): AUTORUN_ENABLED, PHP_OPCACHE_ENABLE, and so on.
EXPOSE 8080
