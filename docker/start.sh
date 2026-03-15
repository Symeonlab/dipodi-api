#!/bin/bash
set -e

echo "=== Dipoddi API - Container Startup ==="

cd /var/www

# Generate .env from environment variables if not already present
if [ ! -f .env ]; then
    echo "Generating .env from environment variables..."

    # Start with required vars
    cat > .env << ENVEOF
APP_NAME=${APP_NAME:-Dipoddi}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_LEVEL=${LOG_LEVEL:-warning}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-dipodi_api}
DB_USERNAME=${DB_USERNAME:-dipodi}
DB_PASSWORD=${DB_PASSWORD:-}

CACHE_DRIVER=${CACHE_DRIVER:-file}
SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}
SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE:-true}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}

REDIS_HOST=${REDIS_HOST:-127.0.0.1}
REDIS_PASSWORD=${REDIS_PASSWORD:-null}
REDIS_PORT=${REDIS_PORT:-6379}

MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_HOST=${MAIL_HOST:-}
MAIL_PORT=${MAIL_PORT:-587}
MAIL_USERNAME=${MAIL_USERNAME:-}
MAIL_PASSWORD=${MAIL_PASSWORD:-}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@dipoddi.com}
MAIL_FROM_NAME=${MAIL_FROM_NAME:-Dipoddi}

CORS_ALLOWED_ORIGINS=${CORS_ALLOWED_ORIGINS:-*}

DIPODI_APP_KEY_HASH=${DIPODI_APP_KEY_HASH:-}

SANCTUM_TOKEN_EXPIRATION=${SANCTUM_TOKEN_EXPIRATION:-10080}
SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS:-localhost}
ENVEOF

    echo ".env generated."
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || grep -q "^APP_KEY=$" .env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
    echo "APP_KEY generated."
fi

# Ensure storage directories exist and have correct permissions
mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create storage link if it doesn't exist
php artisan storage:link --force --no-interaction 2>/dev/null || true

# Cache configuration for performance
php artisan config:cache --no-interaction 2>/dev/null || true
php artisan route:cache --no-interaction 2>/dev/null || true
php artisan view:cache --no-interaction 2>/dev/null || true

echo "=== Starting Supervisord (Nginx + PHP-FPM) ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
