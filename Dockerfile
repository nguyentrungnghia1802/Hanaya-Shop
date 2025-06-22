FROM php:8.2-fpm

# Cài extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy composer files trước để tận dụng cache
COPY composer.json composer.lock ./

# Tạo user không phải root
RUN useradd -ms /bin/bash laravel \
    && chown -R laravel:laravel /var/www/html

# Dùng user laravel để cài gói Laravel
USER laravel

# Cài các gói Laravel bằng Composer (không cần root)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Quay lại root để copy toàn bộ mã nguồn còn lại (tránh lỗi quyền)
USER root

# Copy toàn bộ source code còn lại (views, routes, app, ...)
COPY . .

# Phân quyền lại toàn bộ cho Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Mở cổng Laravel server (Render sẽ dùng cổng này để public)
EXPOSE 10000

# Chạy Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
