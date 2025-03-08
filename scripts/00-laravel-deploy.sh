#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

# echo "generating application key..."
# php artisan key:generate --show


php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Optimize"
php artisan optimize

echo "Running migrations..."
# php artisan migrate --force
# php artisan migrate --seed --force
# php artisan migrate:reset
# php artisan migrate:fresh --seed --force
php artisan db:seed


mkdir storage/framework/{cache/data,views}
mkdir public/attestation
chmod -R 777 storage bootstrap/cache public/attestation
