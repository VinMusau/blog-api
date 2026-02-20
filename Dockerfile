# Use the latest PHP 8.4 image
FROM php:8.4-fpm

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl

# 2. Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions
# Note: mbstring, pdo, and pdo_sqlite are standard, but we'll ensure they are here
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Set working directory
WORKDIR /var/www/backend

# 5. Copy the composer files first for caching
COPY composer.json composer.lock ./

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Run composer install
# We use --ignore-platform-reqs only if you have a weird extension local but not here
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 8. Now copy the rest of the code
COPY . .

# 9. Set permissions and create storage symlink
RUN php artisan storage:link
RUN chown -R www-data:www-data /var/www/backend/storage /var/www/backend/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
