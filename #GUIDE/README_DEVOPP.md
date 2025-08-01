# Hanaya Shop - DevOps Deployment Guide

## 1️⃣ Build & Push Docker Image (Trên máy phát triển - Windows/Mac/Linux)

### Bước 1: Build lại Docker Image
```bash
# Tại thư mục gốc dự án (máy local)
docker build -t assassincreed2k1/hanaya-shop:latest .
```

### Bước 2: Push image lên Docker Hub
```bash
docker push assassincreed2k1/hanaya-shop:latest
```

> **Lưu ý:**
> - Đảm bảo đã đăng nhập Docker Hub: `docker login`
> - Thay đổi code xong, luôn build & push lại image mới!

---

## 2️⃣ Deploy/Update trên server Ubuntu (Production)

### Bước 1: SSH vào server
```bash
ssh root@207.180.242.142
```

### Bước 2: Di chuyển vào thư mục ứng dụng
```bash
cd /opt/hanaya-shop
```

### Bước 3: Pull image mới nhất từ Docker Hub
```bash
sudo docker-compose pull
```

### Bước 4: Restart toàn bộ dịch vụ
```bash
sudo docker-compose up -d
```

### Bước 5: (Nếu có thay đổi database) Chạy migrate
```bash
sudo docker-compose exec app php artisan migrate --force
```

### Bước 6: (Nếu cần) Import lại database từ file SQL
```bash
# Upload file SQL từ máy local lên server
scp "c:/xampp/htdocs/Hanaya-Shop/database/sql/hanaya_shop_backup.sql" root@207.180.242.142:/opt/hanaya-shop/

# Import vào MySQL trong container
docker-compose exec -T db mysql -u root -pTrungnghia2703 hanaya_shop < hanaya_shop_backup.sql
```

### Bước 7: Clear & cache lại Laravel
```bash
sudo docker-compose exec app php artisan cache:clear
sudo docker-compose exec app php artisan config:cache
sudo docker-compose exec app php artisan route:cache
sudo docker-compose exec app php artisan view:cache
```

---

## 3️⃣ Kiểm tra & Quản lý

### Kiểm tra trạng thái container
```bash
sudo docker-compose ps
```

### Xem logs ứng dụng
```bash
sudo docker-compose logs -f app
```

### Truy cập website
- http://207.180.242.142

---

## 4️⃣ Dọn dẹp file deployment không cần thiết (chỉ giữ lại Dockerfile, .dockerignore, deployment/)
```bash
# Trên Windows, xóa các file này:
del deploy-ubuntu.sh update-ubuntu.sh fix-and-deploy.sh docker-fix.sh import-sql.sh docker-compose.production.yml DEPLOYMENT_GUIDE.md QUICK_COMMANDS.md .env.production cleanup-deployment.sh
```

---

## 5️⃣ Lưu ý khi update code
- **KHÔNG cần sửa docker-compose.yml, .env.production, Dockerfile nếu chỉ thay đổi logic code.**
- Chỉ cần build + push image mới, rồi pull + up -d trên server là xong.
- Nếu thêm service, thay đổi port, env, cấu hình hệ thống... mới cần sửa file cấu hình.

---

## 6️⃣ Tối ưu production (khuyến nghị)
- Đặt server gần người dùng cuối (Singapore, VN, Hong Kong)
- Sử dụng CDN cho ảnh, JS, CSS
- Đảm bảo đã build frontend production (`npm run build`)
- Bật gzip/nginx cache, HTTP/2
- Tăng RAM/CPU nếu nhiều user

---

**Mọi thắc mắc về DevOps, hãy xem lại file này hoặc hỏi AI!**
