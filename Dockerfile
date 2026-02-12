FROM php:8.2

ENV COMPOSER_HOME=/composer

RUN apt update && \
    apt install unzip && \
    curl https://getcomposer.org/download/2.9.5/composer.phar -o /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer && \
    usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html
