# Hanaya Shop - Complete Build & Deploy Commands

## 🔨 Trên máy Windows (sau khi Docker Desktop đã chạy):

### Build và Push image mới:
```bash
# Build image với queue workers và DB updates
docker build -t assassincreed2k1/hanaya-shop:latest .

# Push lên Docker Hub
docker push assassincreed2k1/hanaya-shop:latest
```

## 🚀 Trên Ubuntu Server (207.180.242.142):

### Upload script update:
```bash
scp server-update.sh root@207.180.242.142:/opt/hanaya-shop/
```

### Chạy update hoàn chỉnh:
```bash
ssh root@207.180.242.142
cd /opt/hanaya-shop
chmod +x server-update.sh
./server-update.sh
```

## ✅ Các thay đổi đã bao gồm:

1. **Database Updates:**
   - ✅ Bảng `addresses` (user addresses)
   - ✅ Bảng `jobs` (Laravel queue jobs)  
   - ✅ Cột `address_id` và `message` trong bảng `orders`

2. **Application Updates:**
   - ✅ Queue workers với supervisor
   - ✅ Laravel schedule runner
   - ✅ Updated SQL backup file

3. **Docker Updates:**
   - ✅ Multi-process container (Nginx + PHP-FPM + Queue Workers)
   - ✅ Optimized performance và caching

## 🎯 Sau khi hoàn thành:
- Website: http://207.180.242.142
- Queue workers sẽ tự động xử lý background jobs
- Database đã được cập nhật với schema mới
- Tất cả cache được tối ưu cho production

## 📞 Troubleshooting:
```bash
# Kiểm tra queue workers
sudo docker-compose exec app supervisorctl status

# Xem logs workers
sudo docker-compose exec app supervisorctl tail -f laravel-worker:*

# Restart workers nếu cần
sudo docker-compose exec app supervisorctl restart laravel-worker:*
```
