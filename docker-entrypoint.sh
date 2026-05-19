#!/bin/bash

set -e

echo "Starting Laravel application..."

# Wait for database if DB_HOST is set
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
    while ! php -r "try {
        \$pdo = new PDO('pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');
        \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'Database connected!';
    } catch (Exception \$e) {
        sleep(1);
    }" 2>/dev/null; do
        sleep 1
    done
fi

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
