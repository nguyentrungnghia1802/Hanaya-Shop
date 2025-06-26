# Sử dụng image PHP chính thức với Apache
FROM php:8.2-apache

# Cài các extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Cài Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Sao chép toàn bộ mã nguồn vào thư mục làm việc của Apache
COPY . /var/www/html

# Chuyển vào thư mục chứa mã nguồn
WORKDIR /var/www/html

# Tạo các thư mục cần thiết và phân quyền
RUN mkdir -p \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Cài đặt Laravel dependencies bằng Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Mở cổng 8000 (nếu chạy php artisan serve), tùy Render yêu cầu
EXPOSE 8000

# Lệnh mặc định khi container khởi động
CMD php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache \
 && php artisan serve --host=0.0.0.0 --port=8000
