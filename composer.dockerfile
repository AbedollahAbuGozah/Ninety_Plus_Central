FROM composer:latest

ENV COMPOSERUSER=laravel
ENV COMPOSERGROUP=laravel


RUN adduser -g ${COMPOSERGROUP} -s /bin/bash -D ${COMPOSERUSER}
RUN apk add --no-cache \
    bash \
    git \
    curl \
    gcc \
    g++ \
    make \
    autoconf \
    pkgconf \
    openssh \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    exif && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pdo pdo_mysql exif && \
    docker-php-ext-enable exif
