# PHP base image
FROM php:8.2-fpm

# Cài đặt extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tạo thư mục làm việc
WORKDIR /var/www/html

# Sao chép toàn bộ source code (toàn bộ Laravel)
COPY . .

# Cài đặt dependency Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Tạo thư mục nếu chưa có và phân quyền
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port nếu dùng built-in web server (không cần nếu dùng nginx ngoài)
EXPOSE 8000

# ENTRYPOINT không cần thiết nếu bạn dùng docker-compose/nginx để start
