#!/bin/bash

php /var/www/snug/artisan clear-compiled
php /var/www/snug/artisan optimize
php /var/www/snug/artisan view:clear
php /var/www/snug/artisan cache:clear

chown -R ubuntu:www-data /var/www/snug
chmod -R 777 /var/www/snug/storage
chmod -R 777 /var/www/snug/bootstrap

composer install -d /var/www/snug