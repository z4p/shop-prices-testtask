FROM php:8.2-fpm-alpine

# install dependencies
RUN apk update && apk add \
    git \
    bash

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/site

# configs
COPY ./.docker/php/php-fpm.conf /usr/local/etc/php-fpm.conf

# install composer dependencies
RUN cd /var/www/site && curl https://getcomposer.org/composer-stable.phar --output composer.phar && \
    php composer.phar install

# set workdir for any executable cmd to site root by default
WORKDIR /var/www/site

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    /root/.symfony5/bin/symfony server:start