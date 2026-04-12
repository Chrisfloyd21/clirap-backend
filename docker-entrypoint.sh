#!/bin/bash
set -e

echo "🚀 Démarrage..."

# 1. Attente DB
# On laisse le temps à NeonDB de se réveiller
echo "💤 Attente DB (5s)..."
sleep 5

# 2. MIGRATION
# On crée les tables (dont la table 'cache' et 'users') AVANT de mettre en cache la config
echo "🐘 Création des tables (Migration)..."
php artisan migrate --force

# 3. Remplissage des données (Seeders)
# Maintenant que les tables existent, on peut remplir
echo "🌱 Remplissage des données..."
php artisan db:seed --force

# 4. Mise en cache (Maintenant c'est sans danger, les tables existent)
echo "🔥 Mise en cache de la configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🌍 Lancement Apache..."
apache2-foreground