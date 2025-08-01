# Hướng dẫn Cập nhật Hanaya Shop trên Ubuntu Server

## Các bước cập nhật khi có phiên bản mới

### Bước 1: Đăng nhập vào server Ubuntu
Sử dụng SSH để kết nối đến server:
```bash
ssh username@your-server-ip
```

### Bước 2: Di chuyển đến thư mục ứng dụng
```bash
cd /opt/hanaya-shop
```

### Bước 3: Pull phiên bản mới từ Docker Hub
```bash
sudo docker-compose pull
```

### Bước 4: Lưu trữ dữ liệu hiện tại (tùy chọn - để phòng trường hợp cần restore)
```bash
sudo docker-compose exec db mysqldump -u root -p hanaya_shop > backup-$(date +%Y%m%d).sql
```

### Bước 5: Dừng các container đang chạy
```bash
sudo docker-compose down
```

### Bước 6: Khởi động ứng dụng với phiên bản mới
```bash
sudo docker-compose up -d
```

### Bước 7: Chạy migrations để cập nhật database
```bash
# Đợi 10-15 giây để database khởi động hoàn tất
sleep 15

# Chạy migration
sudo docker-compose exec app php artisan migrate --force
```

### Bước 8: Xóa cache và tối ưu hóa ứng dụng
```bash
sudo docker-compose exec app php artisan cache:clear
sudo docker-compose exec app php artisan config:cache
sudo docker-compose exec app php artisan route:cache
sudo docker-compose exec app php artisan view:cache
```

### Bước 9: Kiểm tra logs để đảm bảo ứng dụng chạy đúng
```bash
sudo docker-compose logs -f app
```

## Kiểm tra sau khi cập nhật

### Kiểm tra trạng thái các container
```bash
sudo docker-compose ps
```

### Kiểm tra ứng dụng hoạt động
Truy cập trang web của bạn qua trình duyệt để đảm bảo ứng dụng hoạt động bình thường.

## Xử lý sự cố

### Nếu ứng dụng không hoạt động sau khi cập nhật
```bash
# Kiểm tra logs
sudo docker-compose logs

# Khởi động lại ứng dụng
sudo docker-compose restart

# Trong trường hợp cần thiết, quay lại phiên bản cũ
# (Cần liên hệ với team phát triển)
```

### Nếu cần restore database
```bash
# Dừng ứng dụng
sudo docker-compose down

# Restore database từ backup
sudo docker-compose up -d db
sleep 10
sudo docker-compose exec -T db mysql -u root -p hanaya_shop < backup-YYYYMMDD.sql

# Khởi động lại ứng dụng
sudo docker-compose up -d
```

## Tham khảo thêm
Xem hướng dẫn đầy đủ tại [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) để biết thêm chi tiết về cấu hình và quản lý ứng dụng.
