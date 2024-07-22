FROM php:8-fpm-alpine

ENV PHPGROUP=laravel
ENV PHPUSER=laravel

# Add user and group
RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

# Update PHP-FPM configuration
RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

# Install necessary packages and PHP extensions
RUN apk add --no-cache \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql exif \
    && docker-php-ext-enable exif

# Create necessary directories
RUN mkdir -p /var/www/html/public

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
