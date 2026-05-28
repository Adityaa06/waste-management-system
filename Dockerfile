FROM php:8.2-apache

# 1. Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    sqlite3 \
    libsqlite3-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configure Apache DocumentRoot to point to Laravel's public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 4. Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Install Node.js & NPM (for building Vite assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy project files
COPY . /var/www/html

# 8. Install Composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 9. Install NPM dependencies & build Vite assets
RUN npm install && npm run build

# 10. Prepare SQLite database (Bake migrations and seed data into the image)
RUN cp .env.example .env \
    && php artisan key:generate \
    && touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --force

# 11. Set file ownership and permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 12. Copy entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 13. Expose port 80
EXPOSE 80

# 14. Define Entrypoint and default command
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
