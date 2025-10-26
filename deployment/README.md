# 🐳 Hanaya Shop - Quick Deployment Guide

## 1. Cấu trúc & File chính
- `deployment/docker-compose.prod.yml`: Cấu hình production
- `deployment/.env`: Biến môi trường
- `Dockerfile`: Build ứng dụng
- `nginx/`, `php/`, `mysql/`, `supervisor/`: Cấu hình dịch vụ

## 2. Deploy nhanh
```bash
# Windows:
quick-deploy.bat --seed

# Linux/Mac:
./quick-deploy.sh --seed
```

## 3. Quản lý ứng dụng
```bash`
cd deployment
docker compose -f docker-compose.prod.yml up -d      # Khởi động
docker compose -f docker-compose.prod.yml down --rmi all        # Dừng
docker compose -f docker-compose.prod.yml restart     # Restart
docker compose -f docker-compose.prod.yml logs -f     # Xem logs
docker compose -f docker-compose.prod.yml up -d --build  #Sửa lớn, thay đổi Dockerfile, cài thêm package
```

## 7. Deploy lại application
```bash`
cd c:\xampp\htdocs\Hanaya-Shop\deployment
docker compose -f docker-compose.prod.yml up -d --build   #Dùng để cập nhật code mới vào container
docker compose -f docker-compose.prod.yml build
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force   #Dùng để chạy migration, cập nhật database

docker exec -it hanaya-shop-app bash       #Truy cập bash của Server, có thể dùng lệnh php
Sau đó: php artisan tinker                 #Kiểm tra thông tin

## 4. Truy cập
- Website: http://localhost
- Admin: http://localhost/admin

## 5. Lưu ý
- KHÔNG xoá: `docker-compose.prod.yml`, `.env`, volumes (db_data, storage_data)
- Muốn public web: mở port 80/443, cấu hình domain

---

**Hanaya Shop** - Production-ready với Docker, Nginx, PHP-FPM,