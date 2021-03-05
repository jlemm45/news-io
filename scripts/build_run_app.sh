#!/bin/sh

APP_ENV="${APP_ENV:-development}"

if [ "$APP_ENV" == "development" ] || [ -n "$CIRCLECI" ]; then
  echo "🚀 Wait for DB to start"
  dockerize -wait tcp://database:3306 -timeout 30s
fi

if [ -n "$RUNNING_IN_VOYAGE" ]; then
  echo "🚀 Wait for DB to start"
  dockerize -wait tcp://127.0.0.1:3306 -timeout 30s
fi

echo "🚀 Dump Autoloader"
composer dumpautoload

echo "🚀 Running migrations"
php artisan migrate --force

echo "🚀 Generate App Key"
php artisan key:generate
