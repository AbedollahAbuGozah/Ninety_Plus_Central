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

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy only the necessary files for Composer first
COPY src/composer.json src/composer.lock ./

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer dependencies
RUN composer install --prefer-dist --no-interaction --optimize-autoloader

# Copy the rest of the application files
COPY src /var/www/html

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
