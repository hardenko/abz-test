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
    libpq-dev  # Add this line - this provides the required PostgreSQL libraries

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . /var/www

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Generate key if needed
RUN php artisan key:generate --force

# Set permissions
RUN chmod +x /var/www/artisan

# Expose port 8000
EXPOSE 8000

# Start PHP server
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
