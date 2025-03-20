#!/bin/bash
set -e

composer install --no-progress --no-interaction

chmod -R 775 /var/www/project/storage
chmod -R 775 /var/www/project/bootstrap/cache

php artisan migrate:fresh --seed

php artisan cache:clear
php artisan config:clear
php artisan route:clear

exec docker-php-entrypoint "$@"