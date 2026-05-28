FROM php:8.4-cli

WORKDIR /app

# Install dependencies + PostgreSQL driver
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Jalankan Laravel sesuai port Railway
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}