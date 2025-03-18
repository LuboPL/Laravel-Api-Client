FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libicu-dev \
    gnupg build-essential && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/project
ENV PORT=9001
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
