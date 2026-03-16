#!/bin/bash
set -e

echo "=== Dipoddi API - Container Startup ==="
echo "  $(date '+%Y-%m-%d %H:%M:%S')"

cd /var/www

# ──────────────────────────────────────────────
# [1/5] Generate .env from environment variables
# ──────────────────────────────────────────────
if [ ! -f .env ]; then
    echo "[1/5] Generating .env from environment variables..."

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

    # Secure the .env file
    chmod 640 .env
    chown www-data:www-data .env

    echo "  .env generated (permissions: 640)."
else
    echo "[1/5] .env already exists, skipping generation."
fi

# ──────────────────────────────────────────────
# [2/5] Generate APP_KEY if not set
# ──────────────────────────────────────────────
echo "[2/5] Checking APP_KEY..."
if [ -z "$APP_KEY" ] || grep -q "^APP_KEY=$" .env; then
    echo "  Generating APP_KEY..."
    php artisan key:generate --force --no-interaction
    echo "  APP_KEY generated."
else
    echo "  APP_KEY already set."
fi

# ──────────────────────────────────────────────
# [3/5] Set up storage directories & permissions
# ──────────────────────────────────────────────
echo "[3/5] Setting up storage directories..."
mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
php artisan storage:link --force --no-interaction 2>/dev/null || true

# ──────────────────────────────────────────────
# [4/5] Cache config, routes, views
# ──────────────────────────────────────────────
echo "[4/5] Caching config, routes, views..."
php artisan config:cache --no-interaction 2>/dev/null || true
php artisan route:cache --no-interaction 2>/dev/null || true
php artisan view:cache --no-interaction 2>/dev/null || true
echo "  Cache optimized."

# ──────────────────────────────────────────────
# [5/5] Start Supervisord FIRST, then run DB
#       setup in background so healthcheck passes
# ──────────────────────────────────────────────
echo "[5/5] Starting Supervisord (Nginx + PHP-FPM)..."
echo "=== Dipoddi API — Web server starting ==="

# Run database migrations in background so container starts immediately
# This prevents Sliplane healthcheck timeout during DB wait
(
    sleep 3  # Let supervisord fully start

    echo "[DB] Waiting for database connection..."
    DB_READY=0
    MAX_RETRIES=30
    RETRY_INTERVAL=2

    for i in $(seq 1 $MAX_RETRIES); do
        # Try a raw TCP connection test to DB host
        if php -r "
            \$h = '${DB_HOST:-127.0.0.1}';
            \$p = ${DB_PORT:-3306};
            \$sock = @fsockopen(\$h, \$p, \$errno, \$errstr, 2);
            if (\$sock) { fclose(\$sock); exit(0); }
            exit(1);
        " 2>/dev/null; then
            DB_READY=1
            echo "[DB] Connected (attempt $i/$MAX_RETRIES)."
            break
        fi

        echo "[DB] Waiting... ($i/$MAX_RETRIES)"
        sleep $RETRY_INTERVAL
    done

    if [ $DB_READY -eq 0 ]; then
        echo "[DB] WARNING: Database not available after ${MAX_RETRIES} attempts."
        echo "[DB] API is running but database features are disabled."
        echo "[DB] Set DB_HOST to your MySQL service internal hostname."
        exit 0
    fi

    # Run migrations
    echo "[DB] Running migrations..."
    cd /var/www
    php artisan migrate --force --no-interaction 2>&1 && echo "[DB] Migrations complete." || echo "[DB] WARNING: Migration failed."

    # Auto-seed on first deployment (check if users table is empty)
    USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" = "0" ]; then
        echo "[DB] First deployment detected — running seeders..."
        php artisan db:seed --force --no-interaction 2>&1 && echo "[DB] Seeding complete." || echo "[DB] WARNING: Seeding failed."
    else
        echo "[DB] Database already seeded ($USER_COUNT users found)."
    fi

    # Re-cache config after DB is confirmed working
    php artisan config:cache --no-interaction 2>/dev/null || true
    echo "[DB] Setup complete."
) &

# Start supervisord in foreground (PID 1)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
