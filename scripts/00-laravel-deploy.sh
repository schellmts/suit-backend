#!/bin/bash
set -e

echo ">>> Running composer"
composer install --no-dev --optimize-autoloader

echo ">>> Caching config..."
php artisan config:cache

echo ">>> Caching routes..."
php artisan route:cache

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Build successful!"
