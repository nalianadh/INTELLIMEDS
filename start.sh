#!/bin/bash
set -e

# Laravel setup
php artisan config:cache
php artisan migrate --force

# Start FastAPI in background on port 8001
python -m uvicorn ml_api:app --host 0.0.0.0 --port 8001 &

# Start Laravel as main process on Railway's PORT
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}