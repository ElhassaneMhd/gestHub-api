#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

# echo "generating application key..."
# php artisan key:generate --show
# php artisan config:clear
# php artisan cache:clear
# php artisan route:clear
# php artisan view:clear

# echo "Optimize"
# php artisan optimize

echo "Running migrations..."
php artisan db:seed --force


# mkdir storage/framework/{cache/data,views}
# mkdir public/attestation
# chmod -R 777 storage bootstrap/cache public/attestation
