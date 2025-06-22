FROM php:8.2-fpm

# Cài đặt extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài Composer từ container chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn Laravel vào container
COPY . .

# Tạo user riêng để tránh chạy bằng root
RUN useradd -ms /bin/bash laravel \
    && chown -R laravel:laravel /var/www/html

# Sử dụng user không phải root để chạy Composer
USER laravel

# Cài đặt các dependency của Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Quay lại root để phân quyền cho web server
USER root

# Tạo thư mục storage nếu chưa có và phân quyền
RUN mkdir -p /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Mở cổng Laravel sử dụng
EXPOSE 10000

# Khởi động Laravel server khi container chạy
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
