FROM php:8.2-cli

WORKDIR /src

RUN apt-get update && apt-get install -y \
    git \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /src

RUN composer dump-autoload --optimize

RUN composer install

