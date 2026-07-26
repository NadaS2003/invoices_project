FROM php:8.2-fpm

# Install dependencies & libraries
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Install composer dependencies safely
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expose port and run
EXPOSE 10000

CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan migrate --force && php artisan db:seed --class=permissionTableSeeder --force && php artisan db:seed --class=createAdminUserSeeder --force && php artisan storage:link && php -S 0.0.0.0:$PORT -t public"]
