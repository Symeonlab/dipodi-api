#!/bin/bash
# ==============================================================
# DIPODDI API - Production Deployment Script
# ==============================================================
# Usage:
#   ./deploy.sh              # Full deployment
#   ./deploy.sh --migrate    # Deploy + run migrations
#   ./deploy.sh --seed       # Deploy + run migrations + seeders
#   ./deploy.sh --fresh      # WARNING: Fresh DB + all seeders
# ==============================================================

set -e

APP_DIR="/var/www"
COMPOSE_FILE="docker-compose.yml"
COMPOSE_PROD="docker-compose.production.yml"
APP_CONTAINER="dipodi-app"

echo "================================================"
echo "  DIPODDI API - Production Deployment"
echo "  $(date '+%Y-%m-%d %H:%M:%S')"
echo "================================================"

# ---- Pre-flight checks ----
echo ""
echo "[1/8] Pre-flight checks..."

if [ ! -f ".env" ]; then
    echo "ERROR: .env file not found. Copy .env.production to .env and fill in values."
    exit 1
fi

if grep -q "APP_DEBUG=true" .env; then
    echo "ERROR: APP_DEBUG is true. Set APP_DEBUG=false for production."
    exit 1
fi

if grep -q "APP_ENV=local" .env; then
    echo "ERROR: APP_ENV is local. Set APP_ENV=production."
    exit 1
fi

echo "  Pre-flight checks passed."

# ---- Pull latest code ----
echo ""
echo "[2/8] Pulling latest code..."
git pull origin main

# ---- Install dependencies ----
echo ""
echo "[3/8] Installing PHP dependencies (no-dev)..."
docker exec ${APP_CONTAINER} composer install --no-dev --optimize-autoloader --no-interaction

# ---- Run migrations ----
if [[ "$*" == *"--migrate"* ]] || [[ "$*" == *"--seed"* ]] || [[ "$*" == *"--fresh"* ]]; then
    echo ""
    echo "[4/8] Running database migrations..."
    if [[ "$*" == *"--fresh"* ]]; then
        echo "  WARNING: Running fresh migration (drops all tables)!"
        docker exec ${APP_CONTAINER} php artisan migrate:fresh --force
    else
        docker exec ${APP_CONTAINER} php artisan migrate --force
    fi
else
    echo ""
    echo "[4/8] Skipping migrations (use --migrate flag to run)"
fi

# ---- Run seeders ----
if [[ "$*" == *"--seed"* ]] || [[ "$*" == *"--fresh"* ]]; then
    echo ""
    echo "[5/8] Running database seeders..."
    docker exec ${APP_CONTAINER} php artisan db:seed --force
else
    echo ""
    echo "[5/8] Skipping seeders (use --seed flag to run)"
fi

# ---- Cache optimization ----
echo ""
echo "[6/8] Optimizing application..."
docker exec ${APP_CONTAINER} php artisan config:cache
docker exec ${APP_CONTAINER} php artisan route:cache
docker exec ${APP_CONTAINER} php artisan view:cache
docker exec ${APP_CONTAINER} php artisan event:cache
echo "  Application optimized."

# ---- Restart services ----
echo ""
echo "[7/8] Restarting services..."
docker compose -f ${COMPOSE_FILE} -f ${COMPOSE_PROD} up -d --build
echo "  Services restarted."

# ---- Health check ----
echo ""
echo "[8/8] Running health check..."
sleep 5

HEALTH_CHECK=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health 2>/dev/null || echo "000")

if [ "$HEALTH_CHECK" = "200" ]; then
    echo "  Health check passed (HTTP 200)"
else
    echo "  WARNING: Health check returned HTTP ${HEALTH_CHECK}"
    echo "  Check logs: docker logs dipodi-app"
fi

echo ""
echo "================================================"
echo "  Deployment complete!"
echo "  $(date '+%Y-%m-%d %H:%M:%S')"
echo "================================================"
echo ""
echo "Useful commands:"
echo "  docker logs dipodi-app          # App logs"
echo "  docker logs dipodi-nginx        # Nginx logs"
echo "  docker logs dipodi-queue        # Queue worker logs"
echo "  docker exec dipodi-app php artisan tinker  # Laravel REPL"
echo ""
