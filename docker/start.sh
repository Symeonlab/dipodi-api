#!/bin/bash
set -e

echo "=== Dipoddi API - Container Startup ==="
echo "  $(date '+%Y-%m-%d %H:%M:%S')"

cd /var/www

# ──────────────────────────────────────────────
# [1/7] Generate .env from environment variables
# ──────────────────────────────────────────────
if [ ! -f .env ]; then
    echo "[1/7] Generating .env from environment variables..."

    cat > .env << ENVEOF
APP_NAME=${APP_NAME:-Dipoddi}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://dipodi-api.sliplane.app}

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
SANCTUM_TOKEN_PREFIX=${SANCTUM_TOKEN_PREFIX:-dipoddi_}
SANCTUM_STATEFUL_DOMAINS=${SANCTUM_STATEFUL_DOMAINS:-dipodi-api.sliplane.app}
ENVEOF

    # Secure the .env file — only www-data and root can read
    chmod 640 .env
    chown www-data:www-data .env

    echo "  .env generated (permissions: 640)."
else
    echo "[1/7] .env already exists, skipping generation."
fi

# ──────────────────────────────────────────────
# [2/7] Generate APP_KEY if not set
# ──────────────────────────────────────────────
echo "[2/7] Checking APP_KEY..."
if [ -z "$APP_KEY" ] || grep -q "^APP_KEY=$" .env; then
    echo "  Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
    echo "  APP_KEY generated."
else
    echo "  APP_KEY already set."
fi

# ──────────────────────────────────────────────
# [3/7] Set up storage directories & permissions
# ──────────────────────────────────────────────
echo "[3/7] Setting up storage directories..."
mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create storage link if it doesn't exist
php artisan storage:link --force --no-interaction 2>/dev/null || true

# ──────────────────────────────────────────────
# [4/7] Wait for database to be ready
# ──────────────────────────────────────────────
echo "[4/7] Waiting for database connection..."
DB_READY=0
MAX_RETRIES=30
RETRY_INTERVAL=2

for i in $(seq 1 $MAX_RETRIES); do
    if php artisan db:monitor --databases=mysql 2>/dev/null | grep -q "OK"; then
        DB_READY=1
        echo "  Database connected (attempt $i/$MAX_RETRIES)."
        break
    fi

    # Fallback: try a raw PHP connection test
    if php -r "
        \$h = '${DB_HOST:-127.0.0.1}';
        \$p = ${DB_PORT:-3306};
        \$sock = @fsockopen(\$h, \$p, \$errno, \$errstr, 2);
        if (\$sock) { fclose(\$sock); exit(0); }
        exit(1);
    " 2>/dev/null; then
        DB_READY=1
        echo "  Database port reachable (attempt $i/$MAX_RETRIES)."
        break
    fi

    echo "  Waiting for database... ($i/$MAX_RETRIES)"
    sleep $RETRY_INTERVAL
done

if [ $DB_READY -eq 0 ]; then
    echo "  WARNING: Database not available after ${MAX_RETRIES} attempts."
    echo "  The API will start without database connectivity."
    echo "  Set DB_HOST, DB_USERNAME, DB_PASSWORD env vars in Sliplane."
fi

# ──────────────────────────────────────────────
# [5/7] Run migrations (and seed if first time)
# ──────────────────────────────────────────────
echo "[5/7] Running database migrations..."
if [ $DB_READY -eq 1 ]; then
    # Check if this is a fresh database (no migrations table = first deploy)
    FRESH_DB=0
    php artisan migrate:status --no-interaction 2>/dev/null | grep -q "Migration table not found" && FRESH_DB=1 || true

    # Also check if migrations table exists but is empty
    if [ $FRESH_DB -eq 0 ]; then
        MIGRATION_COUNT=$(php artisan migrate:status --no-interaction 2>/dev/null | grep -c "Ran\|Pending" || echo "0")
        if [ "$MIGRATION_COUNT" = "0" ]; then
            FRESH_DB=1
        fi
    fi

    # Run migrations
    php artisan migrate --force --no-interaction 2>&1 && echo "  Migrations complete." || echo "  WARNING: Migration failed."

    # Auto-seed on first deployment (fresh database)
    if [ $FRESH_DB -eq 1 ]; then
        echo "  First deployment detected — running database seeders..."
        php artisan db:seed --force --no-interaction 2>&1 && echo "  Seeding complete." || echo "  WARNING: Seeding failed."
    fi
else
    echo "  Skipped (database not available)."
fi

# ──────────────────────────────────────────────
# [6/7] Cache config, routes, views
# ──────────────────────────────────────────────
echo "[6/7] Caching config, routes, views..."
php artisan config:cache --no-interaction 2>/dev/null || true
php artisan route:cache --no-interaction 2>/dev/null || true
php artisan view:cache --no-interaction 2>/dev/null || true
echo "  Cache optimized."

# ──────────────────────────────────────────────
# [7/7] Start Supervisord (Nginx + PHP-FPM)
# ──────────────────────────────────────────────
echo "[7/7] Starting Supervisord (Nginx + PHP-FPM)..."
echo "=== Dipoddi API Ready ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
