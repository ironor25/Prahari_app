#!/bin/sh

PORT=${PORT:-80}
sed -i "s/listen 80/listen ${PORT}/g" /etc/nginx/sites-available/default

php artisan config:clear
php artisan cache:clear
php artisan view:clear

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
    php artisan db:seed --force
fi

php-fpm -D

nginx -g "daemon off;"