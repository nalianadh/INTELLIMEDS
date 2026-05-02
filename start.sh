#!/bin/bash
set -e

php artisan config:cache
php artisan migrate --force

# Start FastAPI in background
python -m uvicorn ml_api:app --host 0.0.0.0 --port 8001 &

# Start Laravel as main process
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}