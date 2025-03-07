#!/bin/sh

# copy static files
yes | sudo cp -a  /var/www/html/public/. /var/www/nginx/public

# dump empty .env.local.php for better performance, real environment variables should always be used
composer dump-env prod --empty

# automatically create database schema if not exists
#bin/console doctrine:database:create --if-not-exists
# run database updates
#bin/console doctrine:migration:migrate --no-interaction --allow-no-migration

# start php-fpm
/usr/local/sbin/php-fpm
