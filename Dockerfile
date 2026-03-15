# Dipoddi API - Production Single-Container Setup
# PHP 8.3 + Nginx + Supervisord for Sliplane deployment

FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies (including nginx)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libicu-dev \
    libpq-dev \
    zip \
    unzip \
    supervisor \
    nginx \
    cron \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Create a minimal .env so artisan commands don't fail during build
RUN echo "APP_KEY=base64:dummykeyforbuildonlynotusedatruntime1=" > .env

# Install PHP dependencies (production only)
# --no-scripts prevents artisan commands from running during install
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Copy the rest of the application code
COPY . /var/www

# Now run the post-install scripts (package:discover, filament:upgrade)
RUN composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

# Remove the dummy .env (real one is generated at runtime from env vars)
RUN rm -f .env

# Copy Nginx configuration
COPY docker/nginx/sliplane.conf /etc/nginx/conf.d/default.conf
# Remove default nginx site to avoid conflicts
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

# Copy Supervisord configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy PHP production config
COPY docker/php/production.ini /usr/local/etc/php/conf.d/production.ini

# Copy and set startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache \
    && mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/framework/cache/data

# Create nginx and supervisor directories
RUN mkdir -p /run/nginx /var/log/supervisor \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Expose HTTP port
EXPOSE 80

# Start with the startup script (generates .env, caches config, then starts supervisord)
CMD ["/usr/local/bin/start.sh"]
