#!/bin/bash

chmod -R 775 /var/www/project/storage
chmod -R 775 /var/www/project/bootstrap/cache
composer install --no-progress --no-interaction

php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan serve --port=$PORT --host=0.0.0.0

exec docker-php-entrypoint "$@"