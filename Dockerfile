# On utilise l'image officielle PHP avec Apache inclus
FROM php:8.2-apache

# Installer les extensions système nécessaires pour Laravel et Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Activer le module de réécriture d'URL d'Apache (indispensable pour Laravel)
RUN a2enmod rewrite

# Configurer le dossier racine vers /public (Sécurité Laravel)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf/apache2.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet
COPY . .

# Installer les dépendances PHP (Optimisé pour la prod)
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions à Apache sur les dossiers de stockage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80 pour Render
EXPOSE 80

# Lancer Apache au premier plan
CMD ["apache2-foreground"]