FROM php:8-fpm-alpine

ENV PHPGROUP=laravel
ENV PHPUSER=laravel

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/www/html/public

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

RUN set -ex && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd && \
    docker-php-ext-install -j$(nproc) pdo pdo_mysql exif && \
    docker-php-ext-enable exif

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
