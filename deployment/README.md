# 🐳 Hanaya Shop - Deployment Configuration

> **Production-ready deployment với Nginx + PHP-FPM + MySQL + Redis**

## 📁 Cấu trúc Deployment

```
deployment/
├── docker-compose.prod.yml      # Docker Compose cho production
├── scripts/                     # Scripts deployment
│   ├── deploy.sh               # Deploy script cho Linux/Mac
│   └── deploy.bat              # Deploy script cho Windows
├── nginx/                      # Nginx configuration
│   ├── nginx.conf              # Main Nginx config
│   ├── default.conf            # Site configuration
│   └── certs/                  # SSL certificates
│       └── README.md           # Hướng dẫn SSL
├── php/                        # PHP-FPM configuration
│   ├── php-fpm.conf            # PHP-FPM pool config
│   └── php.ini                 # PHP configuration
├── mysql/                      # MySQL configuration
│   └── mysql.conf              # MySQL performance tuning
└── supervisor/                 # Process manager
    └── supervisord.conf        # Supervisor configuration
```

## 🚀 Quick Start

### 1. Deploy lần đầu (từ thư mục gốc)
```bash
# Linux/Mac
./quick-deploy.sh --seed

# Windows
quick-deploy.bat --seed
```

### 2. Deploy thường xuyên
```bash
# Linux/Mac
./quick-deploy.sh

# Windows
quick-deploy.bat
```

### 3. Deploy thủ công
```bash
cd deployment

# Linux/Mac
./scripts/deploy.sh production

# Windows
scripts\deploy.bat production
```

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

## 🔧 Customization

### Environment Variables
Chỉnh sửa file `.env` trong thư mục gốc:
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

## 🔒 Security Features

- **Rate Limiting**: API và login endpoints
- **Security Headers**: XSS, CSRF, Content-Type protection
- **File Access**: Restricted access to sensitive files
- **PHP Security**: Disabled dangerous functions
- **Database**: Internal networking only
- **SSL**: Full HTTPS support

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
NOTE: Nhớ lệnh    **cd deployment**
```bash
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

## ⚡ Quick Commands

```bash
# Xem tất cả containers
docker ps

# Restart chỉ app
docker compose -f docker-compose.prod.yml restart app

# Xem logs realtime
docker compose -f docker-compose.prod.yml logs -f

# Vào container shell
docker compose -f docker-compose.prod.yml exec app bash

# Clear cache Laravel
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear

# Backup database
docker exec hanaya-shop-db mysqldump -u root -p hanaya_shop > backup_$(date +%Y%m%d).sql
```

---

## 🔧 Sửa Lỗi Thường Gặp

### Route Error (404 Not Found)
```bash
# Clear route cache
docker compose -f docker-compose.prod.yml exec app php artisan route:clear
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
```

### Permission Error
```bash
# Fix permissions
docker compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html
docker compose -f docker-compose.prod.yml exec app chmod -R 755 /var/www/html/storage
```

### Database Connection Error
```bash
# Restart database
docker compose -f docker-compose.prod.yml restart db

# Check database logs
docker compose -f docker-compose.prod.yml logs db
```

---

**Hanaya Shop Deployment** - Optimized for production with Nginx + PHP-FPM 🌸

