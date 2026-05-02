#!/bin/bash
set -e

php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan migrate --force

# Start FastAPI using venv Python
/app/.venv/bin/uvicorn ml_api:app --host 0.0.0.0 --port 8001 &

# Start Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}