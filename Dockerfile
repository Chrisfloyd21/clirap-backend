# -------- STAGE 1 : Build Composer dependencies --------
FROM composer:2 AS composer_stage
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# -------- STAGE 2 : Build the App Image --------
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Copy vendor files from previous stage
COPY --from=composer_stage /app/vendor ./vendor

# Clear old caches (if any)
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

# ---------- START COMMAND ----------
# Run migrations automatically when the container starts
CMD php artisan migrate --force && php-fpm
