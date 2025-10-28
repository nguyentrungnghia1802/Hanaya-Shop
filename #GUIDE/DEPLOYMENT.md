# 🚀 Hanaya Shop - Production Deployment Guide

## 📋 Tổng quan

Hướng dẫn này giúp bạn deploy Hanaya Shop lên production server với Docker và Docker Compose.

---

## 🛠️ Yêu cầu hệ thống

### Minimum Requirements
- **RAM**: 2GB+
- **Storage**: 10GB+ free space
- **CPU**: 2 cores+
- **OS**: Ubuntu 20.04+, CentOS 8+, hoặc Windows Server 2019+

### Software Requirements
- Docker 20.10+
- Docker Compose 2.0+
- Git 2.30+
- SSL Certificate (khuyến nghị)

---

## 🚀 Hướng dẫn deployment

### 1. Chuẩn bị server

```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y
sudo apt install git curl wget -y

# CentOS/RHEL
sudo yum update -y
sudo yum install git curl wget -y
```

### 2. Cài đặt Docker

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add user to docker group
sudo usermod -aG docker $USER
```

### 3. Clone dự án

```bash
git clone https://github.com/your-username/Hanaya-Shop.git
cd Hanaya-Shop
```

### 4. Cấu hình environment

```bash
# Copy và chỉnh sửa file environment
cp .env.production .env

# Chỉnh sửa các thông số sau:
nano .env
```

**Các biến cần cấu hình:**
```env
APP_URL=https://your-domain.com
DB_PASSWORD=your-secure-password
DB_ROOT_PASSWORD=your-root-password
```

### 5. Generate APP_KEY

```bash
# Tạo app key mới
docker run --rm -v $(pwd):/app composer:2 bash -c "cd /app && php artisan key:generate --show"

# Copy key vào file .env
APP_KEY=base64:your-generated-key
```

### 6. Deploy application

```bash
# Quick deployment (recommended)
# Linux/Mac
chmod +x quick-deploy.sh
./quick-deploy.sh

# Windows
quick-deploy.bat

# Manual deployment
# Linux/Mac
cd deployment
chmod +x scripts/deploy.sh
./scripts/deploy.sh production

# Windows
cd deployment
scripts\deploy.bat production
```

### 7. First time setup (chỉ lần đầu)

```bash
# Quick deployment with sample data
./quick-deploy.sh --seed

# Manual deployment with sample data
cd deployment
./scripts/deploy.sh production --seed

# Windows
quick-deploy.bat --seed
```

---

## 🔧 Cấu hình SSL (HTTPS)

### Sử dụng Let's Encrypt

```bash
# Cài đặt certbot
sudo apt install certbot -y

# Tạo certificate
sudo certbot certonly --standalone -d your-domain.com

# Copy certificates vào deployment/nginx/certs/
sudo mkdir -p deployment/nginx/certs
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem deployment/nginx/certs/
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem deployment/nginx/certs/
```

### Cấu hình Domain

Thêm vào `/etc/hosts` hoặc DNS:
```
your-server-ip your-domain.com
```

---

## 📊 Monitoring & Maintenance

### Kiểm tra trạng thái

```bash
# Container status
cd deployment
docker-compose -f docker-compose.prod.yml ps

# Logs
docker-compose -f docker-compose.prod.yml logs -f app

# Resource usage
docker stats
```

### Backup database

```bash
# Manual backup
docker exec hanaya-shop-db mysqldump -u root -p[password] hanaya_shop > backup_$(date +%Y%m%d).sql

# Restore backup
docker exec -i hanaya-shop-db mysql -u root -p[password] hanaya_shop < backup_20250722.sql
```

### Performance tuning

```bash
# Clear cache
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f docker-compose.prod.yml exec app php artisan optimize

# Restart services
docker-compose -f docker-compose.prod.yml restart
```

---

## 🔒 Security Best Practices

### 1. Firewall Configuration
```bash
# UFW (Ubuntu)
sudo ufw allow 22    # SSH
sudo ufw allow 80    # HTTP
sudo ufw allow 443   # HTTPS
sudo ufw enable
```

### 2. Database Security
- Sử dụng mật khẩu mạnh cho database
- Không expose MySQL port ra ngoài (chỉ internal network)
- Regular backup database

### 3. Application Security
- Luôn set `APP_DEBUG=false` trong production
- Sử dụng HTTPS cho tất cả traffic
- Regular update dependencies

---

## 🚨 Troubleshooting

### Common Issues

#### 1. Container không start
```bash
# Check logs
docker-compose -f docker-compose.prod.yml logs app

# Rebuild container
docker-compose -f docker-compose.prod.yml build --no-cache app
```

#### 2. Database connection error
```bash
# Check database container
docker-compose -f docker-compose.prod.yml logs db

# Restart database
docker-compose -f docker-compose.prod.yml restart db
```

#### 3. Permission issues
```bash
# Fix permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```

#### 4. Out of disk space
```bash
# Clean Docker
docker system prune -af
docker volume prune -f

# Clean Laravel
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
```

---

## 📈 Scaling & Performance

### Horizontal Scaling
```yaml
# docker-compose.prod.yml
services:
  app:
    deploy:
      replicas: 3
```

### Load Balancer
Sử dụng Nginx hoặc HAProxy để load balance giữa các container.

### Database Optimization
- Enable query caching
- Add appropriate indexes
- Monitor slow queries

---

## 📞 Support

Nếu gặp vấn đề trong quá trình deployment:

1. Check logs: `docker-compose -f docker-compose.prod.yml logs -f`
2. Verify environment variables
3. Check file permissions
4. Review database connection
5. Monitor system resources

---

## 🎉 Hoàn thành!

Sau khi deployment thành công, bạn có thể truy cập:

- **Website**: https://your-domain.com
- **Admin Panel**: https://your-domain.com/admin
- **API**: https://your-domain.com/api

**Happy deploying! 🌸**
