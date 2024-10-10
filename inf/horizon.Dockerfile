FROM php:8.3-cli

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt update
RUN apt install -y git less libzip-dev libpq-dev
RUN pecl install redis
RUN docker-php-ext-install fileinfo pcntl posix zip pdo pdo_pgsql
RUN docker-php-ext-enable redis

WORKDIR /var/www
