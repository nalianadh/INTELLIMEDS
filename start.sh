#!/bin/bash
set -e

# Clear all caches - do NOT cache config (let it read from env directly)
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Fix libstdc++ for pandas
export LD_LIBRARY_PATH=$(find /nix/store -name "libstdc++.so.6" 2>/dev/null | head -1 | xargs dirname):$LD_LIBRARY_PATH

# Start FastAPI using venv Python
/app/.venv/bin/uvicorn ml_api:app --host 0.0.0.0 --port 8001 &

# Start Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}