# ============================
#   STAGE 1 : Composer Build
# ============================
FROM composer:2 AS composer_stage

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# ============================
#   STAGE 2 : PHP + APACHE
# ============================
FROM php:8.2-apache

# 1. Installation des libs système
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configuration APACHE (Critique pour le 404)
# On active le rewrite
RUN a2enmod rewrite

# On change la racine vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# SUPER IMPORTANT : On autorise le .htaccess pour gérer les routes API
RUN echo '<Directory /var/www/html/public/>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# 3. Copier Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copier les fichiers
COPY . .
COPY --from=composer_stage /app/vendor ./vendor

# 5. Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 6. Finalisation Laravel
RUN composer dump-autoload --optimize

# On ne met pas en cache la config ici pour éviter les bugs d'env au build
# On nettoie tout pour être sûr
RUN php artisan optimize:clear

EXPOSE 80

# 7. COMMANDE DE DÉMARRAGE (Avec Reset DB)
# J'ai remis migrate:fresh car tu as encore des erreurs 500 liées à la DB.
# Une fois que le site marche, remets 'migrate --force' au prochain déploiement.
CMD php artisan migrate:fresh --force --seed && apache2-foreground