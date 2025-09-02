#!/bin/bash

php /var/www/html/artisan key:generate --force

echo "Running Laravel optimizations..."
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

echo "Running database migrations..."
php /var/www/html/artisan migrate --force

php-fpm &

nginx -g 'daemon off;'
