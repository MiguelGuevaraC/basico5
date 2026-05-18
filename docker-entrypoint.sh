#!/bin/bash

set -e

# Wait for database
echo "Waiting for database..."
while ! php -r "try { new PDO('pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'Connected to database'; } catch (Exception \$e) { sleep(1); }"; do
    sleep 1
done

# Run Laravel commands
echo "Running Laravel commands..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Generate OpenAPI documentation
echo "Generating OpenAPI documentation..."
php -r "
define('L5_SWAGGER_CONST_HOST', getenv('APP_URL'));
require __DIR__.'/vendor/autoload.php';
use OpenApi\Generator;
\$generator = new Generator();
\$openapi = \$generator->generate([__DIR__.'/app/Swagger', __DIR__.'/app/Http/Controllers/Api']);
file_put_contents(__DIR__.'/storage/api-docs/api-docs.json', \$openapi->toJson());
echo 'Documentation generated!';
"

# Set permissions again
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Execute the command
exec "$@"
