#!/bin/sh

PORT=${PORT:-80}
sed -i "s/listen 80/listen ${PORT}/g" /etc/nginx/conf.d/default.conf

php artisan config:clear
php artisan cache:clear
php artisan view:clear

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

RUN_MIGRATIONS=${RUN_MIGRATIONS:-true}
if [ "$RUN_MIGRATIONS" != "false" ]; then
    php artisan migrate --force
    php artisan db:seed --force
fi

php-fpm -D

nginx -g "daemon off;"