FROM php:8.2-cli

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock /app/

RUN composer install --no-scripts --no-autoloader

COPY . /app

RUN composer dump-autoload --optimize

