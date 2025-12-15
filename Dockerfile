# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# 1. Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl

# 2. Nettoyage du cache apt pour réduire la taille de l'image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Installation des extensions PHP requises
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# 4. Configuration Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Définition du dossier de travail
WORKDIR /var/www/html

# 7. Copie des fichiers du projet
COPY . .

# 8. Installation des dépendances PHP (Sans scripts pour l'instant pour éviter l'erreur)
RUN composer install  --optimize-autoloader --no-interaction --no-scripts

# 9. Permissions des dossiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 10. Copie et permission du script de démarrage
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Port standard
EXPOSE 80

# Utilisation du script d'entrée personnalisé
ENTRYPOINT ["docker-entrypoint.sh"]