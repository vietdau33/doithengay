FROM php:8.1-fpm-alpine

RUN apk update \
    && apk upgrade \
    && apk add --no-cache \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        jpeg-dev \
        libjpeg \
        libzip-dev \
        libjpeg-turbo-dev \
        zlib \
        unzip \
        npm \
        git

RUN docker-php-ext-install mysqli pdo pdo_mysql gd bcmath zip

RUN deluser www-data &&\
    if getent group www-data ; then delgroup www-data; fi &&\
    addgroup -g 1000 www-data &&\
    adduser -u 1000 -G www-data www-data --disabled-password &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY . /app

RUN chown -R www-data:www-data /app

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
