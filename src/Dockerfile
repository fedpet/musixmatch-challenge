FROM php:8.0-fpm
RUN apt-get update -y && apt-get install -y curl zip unzip
RUN docker-php-ext-install -j$(nproc) pdo_mysql mysqli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
COPY . /app
RUN composer install
COPY php.ini-production /usr/local/etc/php/php.ini-production
COPY php.ini-development /usr/local/etc/php/php.ini-development
CMD php artisan serve --host=0.0.0.0 --port=80
