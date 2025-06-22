# --- Base image ---
    FROM php:8.2-fpm

    # --- Cài extension cần cho Laravel ---
    RUN apt-get update && apt-get install -y \
        git unzip zip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
        && docker-php-ext-install pdo_mysql mbstring zip exif pcntl
    
    # --- Cài Composer ---
    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
    
    # --- Thư mục làm việc ---
    WORKDIR /var/www/html
    
    # --- Copy toàn bộ mã nguồn vào ---
    COPY . .
    
    # --- Tạo thư mục cache nếu chưa có và phân quyền đúng ---
    RUN mkdir -p bootstrap/cache storage \
     && chown -R www-data:www-data bootstrap/cache storage \
     && chmod -R 775 bootstrap/cache storage
    
    # --- Cài các dependency Laravel ---
    RUN composer install --no-interaction --prefer-dist --optimize-autoloader
    
    # --- Laravel sẽ chạy bằng lệnh serve ---
    CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
    