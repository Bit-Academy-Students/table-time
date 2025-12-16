FROM php:8.2-cli

RUN apt-get update && apt-get install -y unzip git

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer global require phpunit/phpunit

ENV PATH="/root/.composer/vendor/bin:${PATH}"

WORKDIR /var/www/html