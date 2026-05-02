#!/bin/bash
set -e

php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "DB_DATABASE value: $DB_DATABASE"
echo "DB_HOST value: $DB_HOST"

export LD_LIBRARY_PATH=$(find /nix/store -name "libstdc++.so.6" 2>/dev/null | head -1 | xargs dirname):$LD_LIBRARY_PATH

/app/.venv/bin/uvicorn ml_api:app --host 0.0.0.0 --port 8001 &

php artisan serve --host=0.0.0.0 --port=${PORT:-8000}