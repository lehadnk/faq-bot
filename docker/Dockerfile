FROM trafex/php-nginx:latest

USER root
RUN apk --no-cache add php8-tokenizer php8-pgsql php8-pdo php8-pdo_pgsql php8-fileinfo php8-redis php8-pcntl php8-posix php8-xmlwriter composer
USER nobody

WORKDIR /var/www/public
