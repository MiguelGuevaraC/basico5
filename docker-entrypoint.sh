#!/bin/bash

set -e

echo "Starting Laravel application..."

# Use array cache for initial setup (no database needed)
export CACHE_DRIVER=array
export SESSION_DRIVER=array

# Clear Laravel caches (using array cache)
echo "Clearing Laravel caches..."
php artisan config:clear --no-ansi
php artisan cache:clear --no-ansi
php artisan route:clear --no-ansi
php artisan view:clear --no-ansi

# Now unset the temp cache settings
unset CACHE_DRIVER
unset SESSION_DRIVER

# Wait a little bit to avoid SSL issues
echo "Waiting for database to stabilize..."
sleep 2

# Run Laravel setup commands
echo "Running Laravel setup commands..."
php artisan config:cache --no-ansi
php artisan route:cache --no-ansi
php artisan view:cache --no-ansi
php artisan migrate --force --no-ansi

# Generate OpenAPI documentation
echo "Generating OpenAPI documentation..."
php artisan openapi:generate --no-ansi

# Ensure permissions are correct
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache server..."
exec "$@"
