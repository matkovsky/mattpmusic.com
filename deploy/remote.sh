#!/usr/bin/env bash
set -euo pipefail

cd /home/matkovs/domains/mattpmusic.com

php artisan down --render="errors::503" || true

php artisan migrate --force
php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

php artisan up
