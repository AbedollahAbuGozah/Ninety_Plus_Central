# First stage: Composer installation
FROM composer:latest AS composer

# Set the working directory
WORKDIR /app

# Copy the composer files
COPY src/composer.json src/composer.lock ./

# Install dependencies
RUN composer install --prefer-dist --no-interaction --optimize-autoloader

# Second stage: PHP-FPM with necessary extensions
FROM php:8-fpm-alpine

# Install necessary system packages
RUN apk update && apk add --no-cache \
    bash \
    git \
    curl \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql exif \
    && docker-php-ext-enable exif

# Set the working directory
WORKDIR /var/www/html

# Copy the application files
COPY src /var/www/html

# Copy the Composer dependencies from the first stage
COPY --from=composer /app/vendor /var/www/html/vendor
COPY --from=composer /app/composer.lock /var/www/html/composer.lock

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
