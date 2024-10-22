#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --seed --force

echo "Optimize"
php artisan optimize

mkdir storage/framework/{cache/data,views}
mkdir public/attestation
chmod -R 777 storage bootstrap/cache public/attestation
