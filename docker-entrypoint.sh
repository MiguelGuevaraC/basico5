#!/bin/bash

set -e

echo "Starting Laravel application..."

# Run Laravel commands
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
