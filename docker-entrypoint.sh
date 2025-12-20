#!/bin/bash
set -e

echo "🚀 Démarrage..."

# Pause pour que NeonDB se réveille
echo "💤 Attente DB (5s)..."
sleep 5

# Nettoyage
echo "🧹 Nettoyage cache..."
php artisan optimize:clear

# Cache
echo "🔥 Mise en cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration & Seed
echo "🐘 Migration & Données..."
php artisan migrate --force
# C'est ici que tes données sont créées
php artisan db:seed --force 

echo "🌍 Apache Start..."
apache2-foreground