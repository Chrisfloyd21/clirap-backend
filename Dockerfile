# ============================
#   STAGE 1 : Composer Build
# ============================
FROM composer:2 AS composer_stage

WORKDIR /app

COPY composer.json composer.lock ./

# On installe les dépendances sans scripts pour l'instant
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# ============================
#   STAGE 2 : PHP Application
# ============================
FROM php:8.2-fpm

# 1. Installation des dépendances système et nettoyage (pour réduire la taille de l'image)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. IMPORTANT : Récupérer l'exécutable Composer dans cette étape aussi
# (Nécessaire pour lancer 'composer dump-autoload' ou 'package:discover')
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 3. Copier les fichiers du projet
COPY . .

# 4. Copier le dossier vendor généré à l'étape 1
COPY --from=composer_stage /app/vendor ./vendor

# 5. Configuration des permissions (Critique pour Laravel)
# On crée les dossiers s'ils n'existent pas et on donne les droits
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# 6. Finalisation de l'autoload et scripts Laravel
# Maintenant que 'artisan' et 'composer' sont là, on peut tout lancer proprement.
RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi

# 7. Optimisation pour la Prod (Cache au lieu de Clear)
# En build Docker, on préfère mettre en cache la config
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000

# 8. Commande de démarrage
# Note: Idéalement, les migrations ne devraient pas être ici pour éviter les conflits si tu scales,
# mais pour une instance unique, ça fonctionne.
CMD sh -c "php artisan migrate --force && php-fpm"