#!/bin/bash
set -e

echo "🚀 Démarrage..."

# 1. Attente DB
# On laisse le temps à NeonDB de se réveiller
echo "💤 Attente DB (5s)..."
sleep 5

# 2. Nettoyage préventif
# On force le driver 'file' ou 'array' juste pour cette commande pour éviter le crash
# si la table cache n'existe pas encore.
echo "🧹 Nettoyage cache..."
php artisan optimize:clear

# 3. MIGRATION (C'est ici le changement important !)
# On crée les tables (dont la table 'cache' et 'users') AVANT de mettre en cache la config
echo "🐘 Création des tables (Migration)..."
php artisan migrate --force

# 4. Remplissage des données (Seeders)
# Maintenant que les tables existent, on peut remplir
echo "🌱 Remplissage des données..."
php artisan db:seed --force 

# 5. Mise en cache (Maintenant c'est sans danger)
echo "🔥 Mise en cache de la configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🌍 Lancement Apache..."
apache2-foreground