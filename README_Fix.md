# Hanaya Shop - Tổng hợp lỗi và cách khắc phục khi triển khai Production bằng Docker

## 1. Đường dẫn truy cập cho khách hàng

Khách hàng muốn truy cập website Hanaya Shop, chỉ cần dùng đường dẫn:

```
http://localhost
```

Nếu deploy trên server thực tế, thay `localhost` bằng domain hoặc IP public của server (ví dụ: http://yourdomain.com hoặc http://123.45.67.89).

---

## 2. Tổng hợp nguyên nhân lỗi, cách sửa và từng bước xử lý

### 2.1. Lỗi gặp phải
- **500 Internal Server Error** khi truy cập web ở môi trường production Docker
- Lỗi "Vite manifest not found at: /var/www/html/public/build/manifest.json" trong log Laravel
- Lỗi APP_KEY, database, Redis, cache, ...

### 2.2. Nguyên nhân chính
- **Thiếu file build frontend (Vite manifest)**: Khi build Docker production, thư mục `public/build` và file `manifest.json` không được tạo ra do chưa chạy lệnh build frontend (`npm run build`).
- Các lỗi khác: APP_KEY chưa sinh, cấu hình database/Redis sai, cache chưa clear, ...

### 2.3. Quá trình tìm kiếm và xử lý lỗi

#### Bước 1: Kiểm tra log Laravel
- Xem file log trong `storage/logs/laravel.log` để xác định lỗi cụ thể
- Phát hiện lỗi: `Vite manifest not found at: /var/www/html/public/build/manifest.json`

#### Bước 2: Kiểm tra Dockerfile và quy trình build
- Nhận ra Dockerfile chưa có bước build frontend (Vite)
- Khi chạy production, không có thư mục `public/build` nên Laravel báo lỗi 500

#### Bước 3: Thêm multi-stage build cho Dockerfile
- Thêm stage sử dụng Node.js để build frontend assets:
  ```Dockerfile
  FROM node:18-alpine AS frontend-builder
  WORKDIR /app
  COPY package*.json ./
  RUN npm ci
  COPY . .
  RUN npm run build
  ```
- Copy kết quả build sang stage PHP:
  ```Dockerfile
  COPY --from=frontend-builder /app/public/build ./public/build
  ```

#### Bước 4: Build lại Docker image
- Sử dụng lệnh:
  ```sh
  docker compose -f docker-compose.prod.yml build --no-cache app
  docker compose -f docker-compose.prod.yml up -d
  ```
- Đảm bảo không dùng `--only=production` khi cài npm để có đủ devDependencies (Vite)

#### Bước 5: Kiểm tra lại website
- Truy cập lại `http://localhost` thấy website hoạt động bình thường, không còn lỗi 500
- Kiểm tra log Laravel không còn lỗi liên quan đến Vite manifest

### 2.4. Các lỗi khác đã xử lý
- **APP_KEY**: Sinh lại key bằng `php artisan key:generate`
- **Database/Redis**: Kiểm tra lại cấu hình `.env` và docker-compose
- **Cache**: Chạy các lệnh `php artisan config:clear`, `cache:clear`, `route:clear`, ...

---

## 3. Kết luận
- Khi deploy Laravel production với Docker, luôn cần build frontend assets (Vite) và copy vào image production
- Kiểm tra log Laravel để xác định nguyên nhân lỗi 500
- Sử dụng multi-stage Docker build để tối ưu và đảm bảo đủ tài nguyên cho cả backend và frontend

---

**Tài liệu này tổng hợp toàn bộ quá trình tìm lỗi, nguyên nhân và cách khắc phục khi triển khai Hanaya Shop bằng Docker.**
