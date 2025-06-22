FROM php:8.2-fpm

# Cài extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Copy Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tạo thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ source code vào container
COPY . .

# ✅ Tạo thư mục cache nếu chưa có và phân quyền đúng
RUN mkdir -p bootstrap/cache \
 && chown -R www-data:www-data bootstrap/cache storage \
 && chmod -R 775 bootstrap/cache storage

# ✅ Cài đặt các package Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ✅ Lệnh chạy Laravel trên Render (mở port 8000)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
