FROM php:8-fpm-alpine

ENV PHPGROUP=laravel
ENV PHPUSER=laravel

# Create a user and group for Laravel
RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

# Update php-fpm configuration to use the Laravel user and group
RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

# Create the public directory for Laravel
RUN mkdir -p /var/www/html/public

# Install dependencies
RUN set -ex && \
    apk update && \
    apk add --no-cache \
        libjpeg-turbo-dev \
        libpng-dev \
        freetype-dev \
        bash \
        autoconf \
        pkgconf \
        g++ \
        make \
        curl \
        git

# Configure and install PHP extensions
RUN set -ex && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install -j$(nproc) pdo pdo_mysql exif && \
    docker-php-ext-enable exif

# Ensure appropriate permissions for all files
RUN chown -R ${PHPUSER}:${PHPGROUP} /var/www/html

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
