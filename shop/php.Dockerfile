ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-apache
RUN groupadd -r apache && useradd -r -g apache apache-user

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable pdo_mysql
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    && docker-php-ext-install pdo_mysql

USER apache-user
COPY . /var/www/html/