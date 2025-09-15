FROM php:7.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    postgresql-dev \
    libpq \
    nginx

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pcntl \
    bcmath

# Enable pdo_pgsql explicitly
COPY docker-php-ext-pdo_pgsql.ini /usr/local/etc/php/conf.d/docker-php-ext-pdo_pgsql.ini
COPY nginx/php-uploads.ini /usr/local/etc/php/conf.d/php-uploads.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy wait-for-it.sh script and make it executable inside the container
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Install shadow package for user/group management
RUN apk add --no-cache shadow

# Create group for shared access with host (moved from docker-compose.yml)
RUN groupadd -g 1001 getcare && usermod -a -G getcare www-data || true

# Set ownership and permissions for shared directories
RUN chown -R www-data:getcare /var/www/html/database/migrations
RUN chmod -R 775 /var/www/html/database/migrations

# Start PHP-FPM
CMD ["php-fpm"]