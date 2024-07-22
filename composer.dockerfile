FROM composer:latest

ENV COMPOSERUSER=laravel
ENV COMPOSERGROUP=laravel


RUN adduser -g ${COMPOSERGROUP} -s /bin/bash -D ${COMPOSERUSER}
RUN apk add --no-cache \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql exif \
    && docker-php-ext-enable exif
