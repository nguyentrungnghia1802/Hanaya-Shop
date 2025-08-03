# Hanaya Shop - Production Deployment Guide

## 🚀 Quick Deployment on Ubuntu Server

### Prerequisites
- Ubuntu 20.04+ server with sudo access
- Internet connection
- At least 2GB RAM and 20GB storage

## 🔄 Quy trình update code sau này:

### 1. Khi bạn thay đổi code (PHP/Laravel/Frontend):
#### Trên máy Windows của bạn:
```bash
git add .
git commit -m "Update feature XYZ"
git push origin main
```
#### Build image mới
```bash
docker build -t assassincreed2k1/hanaya-shop:latest .
docker push assassincreed2k1/hanaya-shop:latest
```
### 2. Update trên server Ubuntu:
#### Trên server Ubuntu:
```bash
cd /opt/hanaya-shop
sudo docker-compose pull
sudo docker-compose up -d
```
#### Chạy migration nếu có thay đổi database
```bash
# Kiểm tra migration status trước
sudo docker-compose -f docker-compose.production.yml exec app php artisan migrate:status

# Chạy migration (đã có kiểm tra table tồn tại)
sudo docker-compose -f docker-compose.production.yml exec app php artisan migrate --force

# Hoặc tạo symlink để đơn giản hơn (chỉ cần làm 1 lần):
# sudo ln -sf docker-compose.production.yml docker-compose.yml
# sudo docker-compose exec app php artisan migrate --force

# Nếu gặp lỗi "Table already exists", rollback và chạy lại:
# sudo docker-compose exec app php artisan migrate:rollback --step=1
# sudo docker-compose exec app php artisan migrate --force
```

**Chi tiết đầy đủ về quy trình cập nhật có trong file [SERVER_UPDATE_GUIDE.md](SERVER_UPDATE_GUIDE.md)**
### Chỉ CẦN THAY ĐỔI file deployment khi:
❗ Thêm service mới (ví dụ: Elasticsearch, Queue worker)

❗ Thay đổi port hoặc expose port mới

❗ Thêm environment variables mới

❗ Thay đổi cấu hình Docker (memory, volumes, networks)

❗ Thêm dependencies system (PHP extensions, system packages)


### One-Command Deployment

```bash
curl -fsSL https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deploy-ubuntu.sh | sudo bash
```

### Manual Deployment Steps

1. **Update system and install Docker**
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y ca-certificates curl gnupg lsb-release

# Install Docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Enable Docker
sudo systemctl enable docker
sudo systemctl start docker
sudo usermod -aG docker $USER
```

2. **Install Docker Compose**
```bash
sudo curl -L "https://github.com/docker/compose/releases/download/v2.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

3. **Create application directory**
```bash
sudo mkdir -p /opt/hanaya-shop
cd /opt/hanaya-shop
```

4. **Create docker-compose.yml**
```bash
sudo wget https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/docker-compose.production.yml -O docker-compose.yml
```

5. **Deploy the application**
```bash
sudo docker-compose pull
sudo docker-compose up -d
```

6. **Wait for services to start and run migrations**
```bash
# Wait 30 seconds for database to be ready
sleep 30

# Run migrations
sudo docker-compose exec app php artisan migrate --force
sudo docker-compose exec app php artisan db:seed --force

# Optimize application
sudo docker-compose exec app php artisan config:cache
sudo docker-compose exec app php artisan route:cache
sudo docker-compose exec app php artisan view:cache
```

7. **Configure firewall**
```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw --force enable
```

## 📊 Docker Images Used

- **Application**: `assassincreed2k1/hanaya-shop:latest`
- **Database**: `mysql:8.0`
- **Cache**: `redis:7-alpine`

## 🔧 Configuration

### Default Credentials
- **Database Password**: `Trungnghia2703`
- **Database User**: `hanaya_user`
- **Database Name**: `hanaya_shop`

### Ports
- **HTTP**: 80
- **HTTPS**: 443  
- **MySQL**: 3306
- **Redis**: 6379

## 📋 Management Commands

### Check application status
```bash
cd /opt/hanaya-shop
sudo docker-compose ps
```

### View application logs
```bash
sudo docker-compose logs -f app
```

### Stop the application
```bash
sudo docker-compose down
```

### Start the application
```bash
sudo docker-compose up -d
```

### Update the application
```bash
sudo docker-compose pull
sudo docker-compose up -d
```

### Access application container
```bash
sudo docker-compose exec app bash
```

### Run Laravel commands
```bash
# Clear cache
sudo docker-compose exec app php artisan cache:clear

# Run migrations
sudo docker-compose exec app php artisan migrate

# Create admin user
sudo docker-compose exec app php artisan make:admin

# View queued jobs
sudo docker-compose exec app php artisan queue:work
```

## 🛠️ Troubleshooting

### Migration Issues

#### "Table already exists" error
```bash
# Option 1: Check which migrations are pending
sudo docker-compose -f docker-compose.production.yml exec app php artisan migrate:status

# Option 2: Mark specific migration as run without executing
# Chỉ dùng khi bảng đã tồn tại và cấu trúc đúng
sudo docker-compose -f docker-compose.production.yml exec app php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::table('migrations')->insertOrIgnore([
    'migration' => '2025_06_20_084648_create_addresses_table',
    'batch' => 1
]);
echo 'Migration marked as run';
"

# Option 3: Fresh migration (CHỈ KHI CẦN THIẾT - SẼ XÓA DỮ LIỆU)
# sudo docker-compose -f docker-compose.production.yml exec app php artisan migrate:fresh --force
```

#### Password reset functionality issues
```bash
# Check if password_reset_tokens table has correct structure
sudo docker-compose -f docker-compose.production.yml exec app php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
\$columns = DB::select('DESCRIBE password_reset_tokens');
foreach(\$columns as \$column) {
    echo \$column->Field . ' - ' . \$column->Type . PHP_EOL;
}
"

# Should only show: email, token, created_at
# If user_id exists, run the remove_user_id migration
```

### View all logs
```bash
sudo docker-compose logs
```

### Restart services
```bash
sudo docker-compose restart
```

### Check disk usage
```bash
sudo docker system df
```

### Clean up unused containers/images
```bash
sudo docker system prune -a
```

### Database issues
```bash
# Connect to MySQL
sudo docker-compose exec db mysql -u root -p

# Backup database
sudo docker-compose exec db mysqldump -u root -p hanaya_shop > backup.sql

# Restore database
sudo docker-compose exec -T db mysql -u root -p hanaya_shop < backup.sql
```

## 🌐 Domain Configuration

### Update APP_URL for your domain
1. Edit the docker-compose.yml file:
```bash
sudo nano /opt/hanaya-shop/docker-compose.yml
```

2. Add your domain to the environment variables:
```yaml
environment:
  - APP_URL=https://yourdomain.com
```

3. Restart the application:
```bash
sudo docker-compose up -d
```

### SSL Certificate (Optional)
For production with custom domain, consider using:
- Let's Encrypt with Certbot
- Cloudflare SSL
- Nginx Proxy Manager

## 📈 Performance Optimization

### Monitor resource usage
```bash
sudo docker stats
```

### Scale services (if needed)
```bash
sudo docker-compose up -d --scale app=2
```

## 🔒 Security Recommendations

1. **Change default passwords**
2. **Use environment files for sensitive data**
3. **Configure firewall properly**
4. **Regular security updates**
5. **Use SSL certificates**
6. **Monitor logs regularly**

## 📞 Support

For issues or questions, please check:
- Application logs: `sudo docker-compose logs app`
- Database logs: `sudo docker-compose logs db`
- System logs: `sudo journalctl -u docker`

---

**Hanaya Shop** - Production ready Laravel e-commerce application
