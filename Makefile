# Dipoddi API - Docker Commands
# Usage: make <command>

.PHONY: help build up down restart logs shell mysql migrate seed fresh test cache

help:
	@echo ""
	@echo "╔══════════════════════════════════════════════════════════════╗"
	@echo "║              DIPODI API - Docker Commands                     ║"
	@echo "╚══════════════════════════════════════════════════════════════╝"
	@echo ""
	@echo "  make build      - Build Docker images"
	@echo "  make up         - Start containers"
	@echo "  make down       - Stop containers"
	@echo "  make restart    - Restart containers"
	@echo "  make logs       - View logs"
	@echo "  make shell      - Open shell in app container"
	@echo "  make mysql      - Open MySQL CLI"
	@echo ""
	@echo "  make install    - Install composer dependencies"
	@echo "  make migrate    - Run database migrations"
	@echo "  make seed       - Run database seeders"
	@echo "  make fresh      - Fresh migrate and seed"
	@echo "  make test       - Run PHPUnit tests"
	@echo "  make cache      - Clear all Laravel caches"
	@echo ""

build:
	docker-compose build --no-cache

up:
	docker-compose up -d
	@echo ""
	@echo "✅ Containers started!"
	@echo "   API: http://localhost:8000"
	@echo ""

down:
	docker-compose down

restart:
	docker-compose restart

logs:
	docker-compose logs -f

shell:
	docker-compose exec app bash

mysql:
	docker-compose exec mysql mysql -u sail -ppassword dipodi_api

install:
	docker-compose exec app composer install

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

seed-dipoddi:
	docker-compose exec app php artisan db:seed --class=DipoddiProgrammeSeeder

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

test:
	docker-compose exec app php artisan test

cache:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear
	@echo "✅ All caches cleared!"

status:
	@docker-compose ps
