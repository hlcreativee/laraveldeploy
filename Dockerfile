FROM php:8.4-cli

WORKDIR /app

# Install system dependencies + PostgreSQL + Node.js
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Node.js (WAJIB untuk Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install JS dependencies + build Vite assets
RUN npm install
RUN npm run build

# Clean dev files
RUN rm -f public/hot

# Clear Laravel cache
RUN php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Railway port fix
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]