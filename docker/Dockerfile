FROM trafex/php-nginx:latest

USER root
RUN apk --no-cache add php81-tokenizer php81-pgsql php81-pdo php81-pdo_pgsql php81-fileinfo php81-redis php81-pcntl php81-posix php81-simplexml php81-xmlwriter php81-zip composer git less
USER nobody

WORKDIR /var/www
