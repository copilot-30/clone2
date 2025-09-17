FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpq \
    nginx \
    unzip \
    && rm -rf /var/cache/apk/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP extensions required for ardagnsrn/ollama-php (and database)
RUN docker-php-ext-install pdo pdo_pgsql curl
RUN docker-php-ext-enable pdo pdo_pgsql curl

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

  
   

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Install shadow package for user/group management
RUN apk add --no-cache shadow

# Create group for shared access with host (moved from docker-compose.yml)
RUN groupadd -g 1001 getcare && usermod -a -G getcare www-data || true
 

# Set working directory
WORKDIR /var/www/html

# Start PHP-FPM
CMD ["php-fpm"]