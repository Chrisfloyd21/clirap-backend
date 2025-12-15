#!/bin/bash

# Arrêter le script si une commande échoue
set -e

echo "🚀 Démarrage du conteneur..."

# 1. Configuration du cache
echo "🔥 Mise en cache de la configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Gestion de la Base de Données (APPROCHE CORRIGÉE)
echo "⏳ Attente que la Base de Données se réveille (10s)..."
sleep 10

echo "🧹 Nettoyage de la base de données (Wipe)..."
# On utilise db:wipe au lieu de migrate:fresh car c'est plus stable sur PostgreSQL
# Si db:wipe échoue (car pas de tables), on continue quand même (|| true)
php artisan db:wipe --force || true

echo "🐘 Lancement des migrations..."
# On lance la migration séparément
php artisan migrate --force --seed

# 3. Démarrage d'Apache
echo "🌍 Lancement d'Apache..."
apache2-foreground