FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && apt-get rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 8080 (Render uses $PORT)
EXPOSE 10000

# Run migrations, seeders, storage link, and start built-in php server
CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan migrate --force && php artisan db:seed --class=permissionTableSeeder --force && php artisan db:seed --class=createAdminUserSeeder --force && php artisan storage:link && php -S 0.0.0.0:$PORT -t public"]
