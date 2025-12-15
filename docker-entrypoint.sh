#!/bin/bash
set -e

echo "🚀 Démarrage du conteneur..."

# 1. Attente de sécurité pour la DB
echo "💤 Pause (10s) pour le réveil de la DB..."
sleep 10

# 2. Cache
echo "🔥 Mise en cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Nettoyage EXPLICITE (étape séparée)
# On tente de supprimer les tables et les types (enums) qui bloquent souvent Postgres
echo "🧹 Nettoyage de la base de données..."
php artisan db:wipe --force --drop-types --drop-views

# 4. Migration EXPLICITE (étape séparée)
# On ne fait pas 'fresh', car on vient de wipe.
echo "🐘 Lancement des migrations..."
php artisan migrate --force --seed

echo "🌍 Lancement d'Apache..."
apache2-foreground