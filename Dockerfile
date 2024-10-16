FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./src /var/www/html

RUN composer install --no-dev --optimize-autoloader

COPY apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80