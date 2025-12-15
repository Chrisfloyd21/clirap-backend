#!/bin/bash
set -e

echo "🚀 Démarrage du conteneur..."

# 1. Attendre que la DB Neon se réveille (Indispensable en gratuit)
echo "💤 Pause de 10s pour le réveil de la Base de Données..."
sleep 10

# 2. Configuration du cache
echo "🔥 Mise en cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Migration brutale
# On utilise migrate:fresh --force qui gère tout (drop + create)
# Si ça plante ici, c'est une erreur de connexion (SSL) ou de droits.
echo "🐘 Lancement de migrate:fresh..."
php artisan migrate:fresh --force --seed

echo "🌍 Lancement d'Apache..."
apache2-foreground