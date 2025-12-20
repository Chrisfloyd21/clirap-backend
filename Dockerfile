FROM php:8.2-apache

# 1. Dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Configuration Apache (CRITIQUE pour le 404)
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# AUTORISER LE .HTACCESS
RUN echo '<Directory /var/www/html/public/>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# 4. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copie des fichiers
WORKDIR /var/www/html
COPY . .

# 6. Installation dépendances Laravel
# On laisse --optimize-autoloader mais on garde les dev-deps pour Faker si besoin, sinon standard.
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# 7. Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 8. Script de démarrage
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]