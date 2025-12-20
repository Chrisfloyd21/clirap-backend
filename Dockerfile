# Utilise l'image officielle PHP 8.2 avec Apache
FROM php:8.2-apache

# 1. Installation des dépendances système
# On ajoute libpq-dev pour PostgreSQL et zip/unzip pour Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Installation des extensions PHP requises par Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# 3. CONFIGURATION APACHE (La partie critique pour réparer le 404)
# Active le module de réécriture d'URL
RUN a2enmod rewrite

# Change la racine du serveur vers le dossier public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# AUTORISE LE .HTACCESS (C'est la clé de ton problème !)
# Cela permet à Laravel de gérer les routes proprement.
RUN echo '<Directory /var/www/html/public/>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# 4. Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Définition du dossier de travail
WORKDIR /var/www/html

# 6. Copie des fichiers du projet
COPY . .

# 7. Installation des dépendances PHP (On inclut faker pour tes factories si besoin)
# On retire --no-dev si tu veux utiliser Faker en prod, sinon remets-le.
# Ici je laisse une install standard optimisée.
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# 8. Permissions des dossiers (Crucial pour les logs et le cache)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 9. Script de démarrage
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Port standard
EXPOSE 80

# Lancement via le script personnalisé
ENTRYPOINT ["docker-entrypoint.sh"]