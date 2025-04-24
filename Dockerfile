FROM php:8.3-cli

# Install dependencies including PostgreSQL development libraries
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-client \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (only those not already loaded)
RUN docker-php-ext-install pdo_pgsql exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# Set permissions
RUN chmod +x /var/www/artisan
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Create an entrypoint script
RUN echo '#!/bin/bash\n\
[ -f .env ] || cp .env.example .env\n\
php artisan key:generate --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan storage:link\n\
php artisan migrate --force\n\
php artisan db:seed --force\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n\
' > /var/www/entrypoint.sh && chmod +x /var/www/entrypoint.sh

# Use the entrypoint script
CMD ["/var/www/entrypoint.sh"]
