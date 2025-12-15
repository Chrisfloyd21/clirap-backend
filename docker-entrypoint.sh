#!/bin/bash

# Arrêter le script si une commande échoue
set -e

echo "🚀 Démarrage du conteneur..."

# 1. Configuration du cache (On le fait ici, car l'ENV est disponible)
echo "🔥 Mise en cache de la configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Gestion de la Base de Données
# ATTENTION : migrate:fresh SUPPRIME TOUTES LES DONNÉES à chaque redémarrage.
# Sur Render Free, le serveur redémarre souvent.
# Si tu veux garder tes données, remplace 'migrate:fresh --seed' par 'migrate --force'
echo "🐘 Migration de la base de données..."
php artisan migrate:fresh --seed --force

# 3. Démarrage d'Apache
echo "🌍 Lancement d'Apache..."
apache2-foreground