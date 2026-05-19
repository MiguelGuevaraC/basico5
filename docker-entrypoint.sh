#!/bin/bash

set -e

echo "Starting Laravel application..."

# Clear config, route, view caches (no database needed)
echo "Clearing caches..."
php artisan config:clear --no-ansi
php artisan route:clear --no-ansi
php artisan view:clear --no-ansi

# Wait for database with retries
echo "Waiting for PostgreSQL database..."
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
            echo 'Database OK!';
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; then
        break
    fi
    RETRY=$((RETRY + 1))
    echo "Retry $RETRY/$MAX_RETRIES - Waiting 2s..."
    sleep 2
done

if [ $RETRY -eq $MAX_RETRIES ]; then
    echo "ERROR: Could not connect to database!"
    exit 1
fi

# Run Laravel setup
echo "Running setup..."
php artisan config:cache --no-ansi
php artisan route:cache --no-ansi
php artisan view:cache --no-ansi
php artisan migrate --force --no-ansi

# Generate OpenAPI docs
echo "Generating OpenAPI docs..."
php artisan openapi:generate --no-ansi

# Create storage link
echo "Creating storage link..."
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link --no-ansi
fi

# Fix ALL permissions
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 775 /var/www/html/storage/app/public || true

echo "Starting Apache..."
exec "$@"
