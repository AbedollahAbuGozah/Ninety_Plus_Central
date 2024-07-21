FROM php:8-fpm-alpine

ENV PHPGROUP=laravel
ENV PHPUSER=laravel

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /var/www/html/public

# Install necessary PHP extensions and oniguruma
RUN apk add --no-cache \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    mysql-client \


RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_mysql exif mbstring

RUN docker-php-ext-enable exif mbstring

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
