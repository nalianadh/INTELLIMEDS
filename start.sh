#!/bin/bash
set -e

php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Don't run migrate to avoid wiping data
# php artisan migrate --force

# Fix libstdc++ for pandas
export LD_LIBRARY_PATH=$(find /nix/store -name "libstdc++.so.6" 2>/dev/null | head -1 | xargs dirname):$LD_LIBRARY_PATH

# Start FastAPI using venv Python
/app/.venv/bin/uvicorn ml_api:app --host 0.0.0.0 --port 8001 &

# Start Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}