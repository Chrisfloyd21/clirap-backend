#!/bin/bash

# On arrête le script si une commande échoue
set -e

# Mettre en cache la config et les routes pour la performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# (Optionnel) Lancer les migrations automatiquement
# Attention : sur le plan gratuit, la DB peut être lente à démarrer,
# parfois cette commande échoue si la DB n'est pas prête.
echo "Running migrations..."
php artisan migrate --force

# Démarrer Apache en premier plan
echo "Starting Apache..."
apache2-foreground