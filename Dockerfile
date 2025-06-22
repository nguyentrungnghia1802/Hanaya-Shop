FROM php:8.2-fpm

# Cài extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Làm việc trong thư mục Laravel
WORKDIR /var/www/html

# Copy composer file và các file cần cho artisan (nhưng chưa copy vendor/)
COPY composer.json composer.lock artisan ./

# Tạo user không phải root
RUN useradd -ms /bin/bash laravel \
    && chown -R laravel:laravel /var/www/html

# Dùng user laravel
USER laravel

# Cài gói Laravel (artisan đã có sẵn)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Quay lại root để copy toàn bộ source code còn lại
USER root

# Copy toàn bộ source (trừ vendor và .env nếu đã gitignore)
COPY . .

# Phân quyền lại cho web server
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Mở cổng Laravel
EXPOSE 10000

# Chạy Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
