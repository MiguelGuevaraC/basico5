#!/bin/bash

set -e

echo "Starting Laravel application..."

# Clear config, route, view caches (no database)
echo "Clearing caches..."
php artisan config:clear --no-ansi
php artisan route:clear --no-ansi
php artisan view:clear --no-ansi

# Wait for database to be available with retry logic
echo "Waiting for PostgreSQL database to become available..."
MAX_RETRIES=30
RETRY=0
while [ $RETRY -lt $MAX_RETRIES ]; do
    if php -r "
        try {
            \$pdo = new PDO(
                'pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo 'Database connection successful!';
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; then
        break
    fi
    RETRY=$((RETRY + 1))
    echo "Retry $RETRY/$MAX_RETRIES - Database not available yet, waiting 2 seconds..."
    sleep 2
done

if [ $RETRY -eq $MAX_RETRIES ]; then
    echo "Error: Could not connect to database after $MAX_RETRIES attempts"
    exit 1
fi

# Run Laravel setup commands
echo "Running Laravel setup..."
php artisan config:cache --no-ansi
php artisan route:cache --no-ansi
php artisan view:cache --no-ansi
php artisan migrate --force --no-ansi

# Generate OpenAPI docs
echo "Generating OpenAPI documentation..."
php artisan openapi:generate --no-ansi

# Fix permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache server..."
exec "$@"
