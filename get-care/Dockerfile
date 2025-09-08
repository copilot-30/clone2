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

# Start PHP-FPM
CMD ["php-fpm"]