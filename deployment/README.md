# 🐳 Hanaya Shop - Deployment Configuration

> **Production-ready deployment với Nginx + PHP-FPM + MySQL + Redis**

---

## 🚀 Hướng Dẫn Deploy Chi Tiết

### 📁 Các File Phụ Trách Deployment

**Trong thư mục `deployment/`:**
- `docker-compose.prod.yml` - Cấu hình chính cho production
- `scripts/deploy.sh` & `scripts/deploy.bat` - Script deploy tự động
- `nginx/` - Cấu hình web server Nginx
- `php/` - Cấu hình PHP-FPM và PHP.ini
- `mysql/` - Cấu hình MySQL database
- `supervisor/` - Quản lý processes
- `.env` - Biến môi trường production

**Ngoài thư mục `deployment/`:**
- `quick-deploy.sh` & `quick-deploy.bat` (thư mục gốc) - Script deploy nhanh
- `Dockerfile` (thư mục gốc) - Build image ứng dụng
- `.dockerignore` (thư mục gốc) - Loại trừ file khi build

---

## 🎯 Trường Hợp 1: Deploy Lần Đầu

### Bước 1: Từ Development sang Production
```bash
# 1. Dừng development server (nếu đang chạy)
# Ctrl+C để dừng php artisan serve và npm run dev

# 2. Commit code (nếu có thay đổi)
git add .
git commit -m "Ready for deployment"

# 3. Deploy lần đầu với sample data
# Từ thư mục gốc dự án:

# Windows:
quick-deploy.bat --seed

# Linux/Mac:
./quick-deploy.sh --seed
```

### Bước 2: Sau Khi Deploy Thành Công

**Những file được tạo trong Docker Desktop:**

**📂 Images (Tab Images):**
- `hanaya-shop-app:latest` - ⚠️ KHÔNG ĐƯỢC XÓA (Application image)
- `mysql:8.0` - ⚠️ KHÔNG ĐƯỢC XÓA (Database image) 
- `redis:7-alpine` - ⚠️ KHÔNG ĐƯỢC XÓA (Cache image)
- `nginxproxy/nginx-proxy` - ⚠️ KHÔNG ĐƯỢC XÓA (Proxy image)

**📂 Containers (Tab Containers):**
- `hanaya-shop-app` - ⚠️ KHÔNG ĐƯỢC XÓA (Main application)
- `hanaya-shop-db` - ⚠️ KHÔNG ĐƯỢC XÓA (Database data)
- `hanaya-shop-redis` - ⚠️ KHÔNG ĐƯỢC XÓA (Cache data)
- `hanaya-shop-proxy` - Có thể xóa nếu không dùng HTTPS

**📂 Volumes (Tab Volumes):**
- `deployment_db_data` - ⚠️ TUYỆT ĐỐI KHÔNG XÓA (Database data)
- `deployment_storage_data` - ⚠️ TUYỆT ĐỐI KHÔNG XÓA (File uploads)
- `deployment_redis_data` - Có thể xóa (Cache sẽ tự tạo lại)

### Bước 3: Truy Cập Ứng Dụng
- **Website**: http://localhost
- **Admin**: http://localhost/admin
- **Database**: localhost:3307

### Bước 4: Đổi Domain
```bash
# Chỉnh sửa file .env trong thư mục deployment/
# Thay đổi dòng:
APP_URL=https://your-domain.com

# Restart containers:
cd deployment
docker compose -f docker-compose.prod.yml restart
```

### Bước 5: Hủy Deploy Để Về Development
```bash
# Từ thư mục deployment/
cd deployment

# Dừng tất cả containers:
docker compose -f docker-compose.prod.yml down

# Về thư mục gốc:
cd ..

# Chạy development:
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

---

## 🔄 Trường Hợp 2: Khởi Động Lại Sau Khi Tắt Máy

### Kiểm Tra Trạng Thái
```bash
# Kiểm tra containers còn không:
docker ps -a

# Nếu containers vẫn còn nhưng đã dừng:
cd deployment
docker compose -f docker-compose.prod.yml start

# Nếu không có containers nào (hiếm khi xảy ra):
docker compose -f docker-compose.prod.yml up -d
```

### Xác Nhận
✅ **Những file deployed trước đó VẪN CÒN trong Docker Desktop**
- Images, Volumes, Networks đều được giữ lại
- Chỉ cần start containers là ứng dụng hoạt động ngay

**Lưu ý**: Nếu bạn đã xóa nhầm containers, data vẫn an toàn trong Volumes. Chỉ cần chạy lại:
```bash
cd deployment
docker compose -f docker-compose.prod.yml up -d
```

---

## 📁 Trường Hợp 3: Di Chuyển File Trong Deployment

### Quy Tắc Đường Dẫn

**Nếu di chuyển `deployment/` sang vị trí khác:**
```bash
# VD: Di chuyển từ C:\xampp\htdocs\Hanaya-Shop\deployment\
# Sang: C:\deploy\hanaya\

# Cập nhật lệnh:
cd C:\deploy\hanaya\
docker compose -f docker-compose.prod.yml up -d
```

**Nếu đổi tên thư mục `deployment/` thành tên khác:**
```bash
# VD: Đổi thành "production"
cd production
docker compose -f docker-compose.prod.yml up -d

# Cập nhật script quick-deploy.bat:
# Thay "deployment/" thành "production/"
```

### ⚠️ Files TUYỆT ĐỐI KHÔNG ĐƯỢC XÓA:

**Cấp độ NGHIÊM TRỌNG:**
- `docker-compose.prod.yml` - Toàn bộ cấu hình deployment
- `volumes/` (nếu có) - Data persistence
- `.env` - Environment production

**Cấp độ QUAN TRỌNG:**
- `nginx/default.conf` - Web server sẽ lỗi 502
- `php/php-fpm.conf` - Application không start
- `mysql/mysql.conf` - Database performance thấp
- `supervisor/supervisord.conf` - Services không tự động start

**Có thể xóa/tạo lại:**
- `scripts/` - Chỉ ảnh hưởng automation
- `nginx/certs/` - Chỉ ảnh hưởng HTTPS
- `backups/` - Không ảnh hưởng hoạt động

---

## 📁 Cấu trúc Deployment

```
deployment/
├── docker-compose.prod.yml      # ⚠️ CORE - Cấu hình deployment
├── .env                        # ⚠️ CORE - Environment variables
├── scripts/                    # Automation scripts
│   ├── deploy.sh              # Deploy cho Linux/Mac  
│   └── deploy.bat             # Deploy cho Windows
├── nginx/                     # ⚠️ QUAN TRỌNG - Web server
│   ├── nginx.conf             # Main Nginx config
│   ├── default.conf           # Site configuration
│   └── certs/                 # SSL certificates
│       └── README.md          # Hướng dẫn SSL
├── php/                       # ⚠️ QUAN TRỌNG - PHP runtime
│   ├── php-fpm.conf           # PHP-FPM pool config
│   └── php.ini                # PHP configuration
├── mysql/                     # ⚠️ QUAN TRỌNG - Database config
│   └── mysql.conf             # MySQL performance tuning
└── supervisor/                # ⚠️ QUAN TRỌNG - Process manager
    └── supervisord.conf       # Supervisor configuration
```

---

## 🚀 Quick Start Commands

### Deploy Lần Đầu
```bash
# Windows (từ thư mục gốc):
quick-deploy.bat --seed

# Linux/Mac (từ thư mục gốc):
./quick-deploy.sh --seed
```

### Deploy Thường Xuyên
```bash
# Windows:
quick-deploy.bat

# Linux/Mac:
./quick-deploy.sh
```

### Kiểm Tra & Quản Lý
```bash
# Xem status:
cd deployment
docker compose -f docker-compose.prod.yml ps

# Xem logs:
docker compose -f docker-compose.prod.yml logs -f

# Restart:
docker compose -f docker-compose.prod.yml restart

# Dừng:
docker compose -f docker-compose.prod.yml down

# Start lại:
docker compose -f docker-compose.prod.yml up -d
```

---

## ⚙️ Cấu hình Chi tiết

### Nginx
- **Performance**: Worker processes auto, keepalive optimization
- **Security**: Rate limiting, security headers, file access restrictions
- **Caching**: Static files caching, gzip compression
- **SSL**: Ready for HTTPS with Let's Encrypt

### PHP-FPM
- **Pool**: Dynamic pool với 50 max children
- **OPcache**: Enabled với memory optimization
- **Sessions**: Redis-based sessions
- **Error handling**: Production-ready error logging

### MySQL
- **Performance**: InnoDB optimization, query cache
- **Security**: Authentication plugin, connection limits
- **Monitoring**: Slow query logging
- **Timezone**: Asia/Ho_Chi_Minh

### Redis
- **Persistence**: AOF enabled
- **Memory**: Optimized for caching và sessions
- **Network**: Internal cluster networking

### Supervisor
- **Nginx**: Web server management
- **PHP-FPM**: Application server
- **Laravel Workers**: Queue processing (2 processes)
- **Laravel Scheduler**: Cron jobs automation

---

## 🔧 Customization

### Environment Variables
Chỉnh sửa file `.env` trong thư mục deployment:
```env
APP_URL=https://your-domain.com
DB_PASSWORD=your-secure-password
REDIS_PASSWORD=your-redis-password
```

### Scaling
Để tăng performance, chỉnh sửa `docker-compose.prod.yml`:
```yaml
services:
  app:
    deploy:
      replicas: 3  # Multiple app instances
```

### SSL Configuration
1. Đặt certificates vào `nginx/certs/`
2. Certificates cần có tên: `fullchain.pem` và `privkey.pem`
3. Restart containers: `docker-compose restart`

---

## 🌐 Truy Cập Dự Án

### Links Truy Cập
- **Website chính (khách hàng)**: `http://localhost:80`
- **Admin Dashboard**: `http://localhost:80/admin`
- **User Dashboard**: `http://localhost:80/dashboard` (sau khi đăng nhập)

### Thông tin Database
- **Host**: `localhost`
- **Port**: `3307`
- **Database**: `hanaya_shop`
- **Username**: `root`
- **Password**: Xem trong file `.env`

---

## 🛠️ Phát Triển Tiếp

### 1. Dừng Deploy để Phát Triển
```bash
cd deployment

# Dừng tất cả containers
docker compose -f docker-compose.prod.yml down

# Hoặc chỉ dừng app container (giữ database)
docker compose -f docker-compose.prod.yml stop app
```

### 2. Chạy Development Mode
```bash
# Về thư mục gốc
cd ..

# Cài đặt dependencies (nếu chưa có)
composer install
npm install

# Copy .env và cấu hình
cp .env.example .env
php artisan key:generate

# Migrate database (sử dụng DB từ production)
php artisan migrate

# Chạy development server
php artisan serve --host=0.0.0.0 --port=8000

# Chạy frontend assets (terminal khác)
npm run dev
```

### 3. Database Development
- **Sử dụng Production DB**: Kết nối tới `localhost:3307`
- **Database riêng**: Chạy `php artisan migrate --seed` với DB mới

---

## 🚀 Deploy Lại Dự Án

### Deploy Nhanh (Recommended)
```bash
# Từ thư mục gốc
./quick-deploy.bat

# Hoặc Linux/Mac
./quick-deploy.sh
```

### Deploy Thủ Công
```bash
# Vào thư mục deployment
cd deployment

# Build và chạy containers
docker compose -f docker-compose.prod.yml up --build -d

# Xem logs để check
docker compose -f docker-compose.prod.yml logs -f app
```

### Deploy với Database Reset
```bash
# Xóa hoàn toàn và tạo lại
docker compose -f docker-compose.prod.yml down -v
./quick-deploy.bat --seed
```

---

## 📝 Workflow Phát Triển

### 1. Phát Triển Feature Mới
```bash
# Dừng production
docker compose -f docker-compose.prod.yml down

# Chạy development
php artisan serve
npm run dev
```

### 2. Test Feature
```bash
# Test local
php artisan test

# Deploy staging
docker compose -f docker-compose.prod.yml up --build -d
```

### 3. Deploy Production
```bash
# Commit code
git add .
git commit -m "New feature"
git push

# Deploy
./quick-deploy.bat
```

---

## 📊 Monitoring Commands

```bash
# Xem status containers
docker-compose -f docker-compose.prod.yml ps

# Xem logs realtime
docker-compose -f docker-compose.prod.yml logs -f app

# Xem resource usage
docker stats

# Access container shell
docker-compose -f docker-compose.prod.yml exec app bash

# Database backup
docker exec hanaya-shop-db mysqldump -u root -p hanaya_shop > backup.sql
```

---

## 🔒 Security Features

- **Rate Limiting**: API và login endpoints
- **Security Headers**: XSS, CSRF, Content-Type protection
- **File Access**: Restricted access to sensitive files
- **PHP Security**: Disabled dangerous functions
- **Database**: Internal networking only
- **SSL**: Full HTTPS support

---

## 🚨 Troubleshooting

### Container không start
```bash
docker-compose -f docker-compose.prod.yml logs app
docker-compose -f docker-compose.prod.yml build --no-cache app
```

### Database connection issues
```bash
docker-compose -f docker-compose.prod.yml logs db
docker-compose -f docker-compose.prod.yml restart db
```

### Permission problems
```bash
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html
```

### Performance issues
```bash
# Clear all caches
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear

# Rebuild optimizations
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
```

---

**Hanaya Shop Deployment** - Optimized for production with Nginx + PHP-FPM 🌸