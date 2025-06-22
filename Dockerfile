FROM php:8.2-fpm

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravel source
WORKDIR /var/www/html
COPY . .

# Tạo thư mục storage và cấp quyền
RUN mkdir -p storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Mở cổng 8000
EXPOSE 8000

# Chạy Laravel HTTP server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
