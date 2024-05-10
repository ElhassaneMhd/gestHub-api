#!/usr/bin/env bash
echo "Running composer"
composer global update --working-dir=/var/www/html
composer install --no-dev  --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --seed --force
