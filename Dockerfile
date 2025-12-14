# ============================
#   STAGE 1 : Composer Build
# ============================
FROM composer:2 AS composer_stage

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# ============================
#   STAGE 2 : PHP + APACHE (Changement ici)
# ============================
FROM php:8.2-apache

# 1. Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Activer le module "Rewrite" d'Apache (INDISPENSABLE pour Laravel)
RUN a2enmod rewrite

# 3. Configurer Apache pour pointer vers le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Copier Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Copier l'application
COPY . .
COPY --from=composer_stage /app/vendor ./vendor

# 6. Permissions
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 7. Autoload et Scripts
RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi

# 8. Caches
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# 9. Port (Render détectera le port 80 automatiquement)
EXPOSE 80

# 10. Démarrage (Migrate + Apache)
# On garde migrate --force. Si ça plante, remets migrate:fresh temporairement comme avant.
CMD php artisan migrate --force && apache2-foreground