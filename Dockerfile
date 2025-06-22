FROM php:8.2-fpm

# Cài extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc
WORKDIR /var/www/html

# ⚠️ Copy toàn bộ source code Laravel trước khi chạy composer
COPY . .

# Tạo user không phải root
RUN useradd -ms /bin/bash laravel \
    && chown -R laravel:laravel /var/www/html

# Dùng user laravel để chạy composer
USER laravel

# ✅ Composer sẽ hoạt động tốt nếu toàn bộ mã nguồn đã có
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Quay lại root để phân quyền
USER root

# Tạo thư mục cần thiết & cấp quyền
RUN mkdir -p /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
