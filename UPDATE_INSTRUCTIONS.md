# 🚀 Hanaya Shop - Update Guide

## 📋 Cách Update Bản Mới Trên Ubuntu Server

### 🔥 Cách 1: Update Nhanh (Recommended)

```bash
# 1. Vào thư mục project
cd ~/hanayashop

# 2. Chạy lệnh update nhanh
./quick-update.sh
```

### 🛠️ Cách 2: Update Thủ Công

```bash
# 1. Vào thư mục project
cd ~/hanayashop

# 2. Chạy script update
chmod +x update-ubuntu.sh
./update-ubuntu.sh
```

### 📝 Cách 3: Update Từng Bước

```bash
# 1. Vào thư mục project
cd ~/hanayashop

# 2. Dừng ứng dụng
docker-compose -f docker-compose.production.yml exec -T app php artisan down

# 3. Pull image mới nhất
docker-compose -f docker-compose.production.yml pull app

# 4. Restart container
docker-compose -f docker-compose.production.yml stop app
docker-compose -f docker-compose.production.yml rm -f app
docker-compose -f docker-compose.production.yml up -d app

# 5. Chờ container khởi động
sleep 20

# 6. Chạy migration (chỉ khi có migration mới)
docker-compose -f docker-compose.production.yml exec -T app php artisan migrate:status
docker-compose -f docker-compose.production.yml exec -T app php artisan migrate --force

# 7. Clear cache
docker-compose -f docker-compose.production.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan route:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan view:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan cache:clear

# 8. Set quyền
docker-compose -f docker-compose.production.yml exec -T app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data storage bootstrap/cache

# 9. Cache lại
docker-compose -f docker-compose.production.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan view:cache

# 10. Bật lại ứng dụng
docker-compose -f docker-compose.production.yml exec -T app php artisan up
```

## 🔍 Kiểm Tra Trạng Thái

```bash
# Kiểm tra container đang chạy
docker-compose -f docker-compose.production.yml ps

# Kiểm tra logs
docker-compose -f docker-compose.production.yml logs app

# Test ứng dụng
curl -f http://localhost/health
```

## 🆘 Troubleshooting

### Lỗi Permission
```bash
docker-compose -f docker-compose.production.yml exec -T app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data storage bootstrap/cache
```

### Lỗi Cache
```bash
docker-compose -f docker-compose.production.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan cache:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan view:clear
```

### Restart Hoàn Toàn
```bash
docker-compose -f docker-compose.production.yml down
docker-compose -f docker-compose.production.yml up -d
```

## 📊 Thông Tin Phiên Bản

- **Docker Image**: `assassincreed2k1/hanaya-shop:latest`
- **Size**: ~758MB
- **Last Updated**: $(date)

## 🌐 URLs

- **Application**: http://localhost hoặc http://YOUR_SERVER_IP
- **Admin**: http://localhost/admin hoặc http://YOUR_SERVER_IP/admin

---
✨ **Happy Updating!** ✨
