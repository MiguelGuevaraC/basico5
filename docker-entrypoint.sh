#!/bin/bash

set -e

echo "Starting Laravel application..."

# Clear only config, route, view caches (these don't use the database cache driver)
echo "Clearing Laravel config, route, and view caches..."
php artisan config:clear --no-ansi
php artisan route:clear --no-ansi
php artisan view:clear --no-ansi

# Wait a little to let database connection settle
echo "Waiting for database to be ready..."
sleep 3

# Now run Laravel setup commands
echo "Running Laravel setup commands..."
php artisan config:cache --no-ansi
php artisan route:cache --no-ansi
php artisan view:cache --no-ansi
php artisan migrate --force --no-ansi

# Generate OpenAPI documentation
echo "Generating OpenAPI documentation..."
php artisan openapi:generate --no-ansi

# Set correct permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache server..."
exec "$@"
