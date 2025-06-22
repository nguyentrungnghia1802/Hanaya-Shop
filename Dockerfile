FROM php:8.2-fpm

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set thư mục làm việc
WORKDIR /var/www/html

# Copy source code
COPY . .

# Cài các gói Laravel bằng Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Tạo thư mục cần thiết & phân quyền
RUN mkdir -p storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage

# Mở cổng cho Render (sử dụng 10000)
EXPOSE 10000

# Chạy Laravel Server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
