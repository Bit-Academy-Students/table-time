FROM php:8.2-cli

RUN apt-get update && apt-get install -y unzip git \
    && curl -L https://phar.phpunit.de/phpunit-10.phar -o /usr/local/bin/phpunit \
    && chmod +x /usr/local/bin/phpunit

WORKDIR /var/www/html
