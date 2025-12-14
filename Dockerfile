# On utilise l'image officielle PHP avec Apache inclus
FROM php:8.2-apache

# Installer les extensions système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Activer le module de réécriture d'URL d'Apache
RUN a2enmod rewrite

# Configurer le dossier racine vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# --- CORRECTION ICI ---
# Modification de la configuration des sites disponibles
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# Modification de la configuration globale d'Apache (Le chemin est corrigé)
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions à Apache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# On lance Apache uniquement. Plus de migration ici.
CMD ["apache2-foreground"]