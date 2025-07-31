# Hanaya Shop - Quick Commands

## 🚀 Deployment Commands

### Ubuntu Server - One Command Deploy
```bash
curl -fsSL https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deploy-ubuntu.sh | sudo bash
```

### Manual Docker Commands
```bash
# Pull and run the application
docker run -d \
  --name hanaya-shop-app \
  -p 80:80 \
  -e APP_ENV=production \
  -e DB_HOST=your-db-host \
  -e DB_PASSWORD=Trungnghia2703 \
  assassincreed2k1/hanaya-shop:latest

# With full stack using docker-compose
cd /opt/hanaya-shop
sudo docker-compose up -d
```

## 🔧 Management Commands

```bash
# Check application status
docker-compose ps

# View logs
docker-compose logs -f app

# Update application
docker-compose pull && docker-compose up -d

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear

# Database backup
docker-compose exec db mysqldump -u root -p hanaya_shop > backup.sql

# Access container
docker-compose exec app bash
```

## 🌐 URLs After Deployment

- **Application**: http://YOUR_SERVER_IP
- **Health Check**: http://YOUR_SERVER_IP/health
- **Admin Panel**: http://YOUR_SERVER_IP/admin

## 📋 Default Credentials

- **Database**: 
  - Host: localhost (or container name 'db')
  - Database: hanaya_shop
  - Username: hanaya_user
  - Password: Trungnghia2703

- **Email Settings**:
  - SMTP: smtp.gmail.com
  - Port: 587
  - Username: assassincreed2k1@gmail.com
  - App Password: tijrvguflmbctaba

## 🔒 Security Notes

- Change default passwords in production
- Configure SSL certificates
- Update firewall rules as needed
- Regular security updates

## 📞 Support

For issues:
1. Check application logs: `docker-compose logs app`
2. Check system resources: `docker stats`
3. Verify network connectivity
4. Check DNS settings
