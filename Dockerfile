FROM php:8.2-fpm

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravel source
WORKDIR /var/www/html

# Cấp quyền cho Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 8000
