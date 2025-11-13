FROM php:8.2-fpm

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html