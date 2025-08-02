# Hanaya Shop - Ubuntu Deployment Guide

This guide will help you deploy the Hanaya Shop application on Ubuntu using Docker.

## Prerequisites

1. **Ubuntu Server** (18.04 or higher)
2. **Docker** installed
3. **Docker Compose** installed
4. **Internet connection** for pulling images

## Quick Installation

### Install Docker and Docker Compose (if not already installed)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add user to docker group
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Logout and login again for group changes to take effect
```

## Deployment

### First Time Deployment

1. **Download the deployment script:**
   ```bash
   wget https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deploy-ubuntu.sh
   chmod +x deploy-ubuntu.sh
   ```

2. **Run the deployment script:**
   ```bash
   ./deploy-ubuntu.sh
   ```

3. **Access your application:**
   - Local: http://localhost
   - External: http://YOUR_SERVER_IP

### Update Existing Deployment

1. **Download the update script:**
   ```bash
   wget https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/update-ubuntu.sh
   chmod +x update-ubuntu.sh
   ```

2. **Run the update script:**
   ```bash
   ./update-ubuntu.sh
   ```

## Using Makefile (Alternative)

If you have the full repository:

```bash
# First deployment
make deploy

# Update application
make update

# View logs
make logs

# Stop services
make stop

# Show status
make status
```

## Manual Deployment Steps

If you prefer manual deployment:

### 1. Create docker-compose.production.yml

```yaml
services:
  app:
    image: assassincreed2k1/hanaya-shop:latest
    container_name: hanaya-shop-app
    ports:
      - "80:80"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_URL=http://localhost
      - DB_HOST=db
      - DB_DATABASE=hanaya_shop
      - DB_USERNAME=hanaya_user
      - DB_PASSWORD=Trungnghia2703
      - REDIS_HOST=redis
      - CACHE_DRIVER=redis
      - SESSION_DRIVER=redis
      - QUEUE_CONNECTION=redis
    volumes:
      - storage_data:/var/www/html/storage/app
      - logs_data:/var/www/html/storage/logs
    depends_on:
      - db
      - redis
    networks:
      - hanaya-network
    restart: unless-stopped
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

  db:
    image: mysql:8.0
    container_name: hanaya-shop-db
    environment:
      - MYSQL_ROOT_PASSWORD=Trungnghia2703
      - MYSQL_DATABASE=hanaya_shop
      - MYSQL_USER=hanaya_user
      - MYSQL_PASSWORD=Trungnghia2703
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - hanaya-network
    restart: unless-stopped
    command: --default-authentication-plugin=mysql_native_password

  redis:
    image: redis:7-alpine
    container_name: hanaya-shop-redis
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    networks:
      - hanaya-network
    restart: unless-stopped
    command: redis-server --appendonly yes

volumes:
  db_data:
  redis_data:
  storage_data:
  logs_data:

networks:
  hanaya-network:
    driver: bridge
```

### 2. Start Services

```bash
docker-compose -f docker-compose.production.yml up -d
```

### 3. Run Initial Setup

```bash
# Wait for services to start
sleep 30

# Run migrations
docker-compose -f docker-compose.production.yml exec -T app php artisan migrate --force

# Fix permissions
docker-compose -f docker-compose.production.yml exec -T app mkdir -p storage/framework/views
docker-compose -f docker-compose.production.yml exec -T app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data storage bootstrap/cache

# Cache optimization
docker-compose -f docker-compose.production.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan view:cache
```

## Useful Commands

### View Logs
```bash
# Application logs
docker-compose -f docker-compose.production.yml logs -f app

# All services logs
docker-compose -f docker-compose.production.yml logs -f

# Database logs
docker-compose -f docker-compose.production.yml logs -f db
```

### Service Management
```bash
# Check status
docker-compose -f docker-compose.production.yml ps

# Restart services
docker-compose -f docker-compose.production.yml restart

# Stop services
docker-compose -f docker-compose.production.yml down

# Execute commands in container
docker-compose -f docker-compose.production.yml exec app bash
```

### Application Commands
```bash
# Clear cache
docker-compose -f docker-compose.production.yml exec app php artisan cache:clear

# Run migrations
docker-compose -f docker-compose.production.yml exec app php artisan migrate

# Check queue status
docker-compose -f docker-compose.production.yml exec app php artisan queue:work --once
```

## Troubleshooting

### Common Issues

1. **Port 80 already in use:**
   ```bash
   sudo netstat -tlnp | grep :80
   sudo systemctl stop apache2  # if Apache is running
   sudo systemctl stop nginx    # if Nginx is running
   ```

2. **Permission issues:**
   ```bash
   docker-compose -f docker-compose.production.yml exec app chmod -R 775 storage bootstrap/cache
   docker-compose -f docker-compose.production.yml exec app chown -R www-data:www-data storage bootstrap/cache
   ```

3. **Database connection issues:**
   ```bash
   # Check if database is running
   docker-compose -f docker-compose.production.yml ps
   
   # Check database logs
   docker-compose -f docker-compose.production.yml logs db
   ```

4. **Application not accessible:**
   ```bash
   # Check health endpoint
   curl http://localhost/health
   
   # Check application logs
   docker-compose -f docker-compose.production.yml logs app
   ```

### Health Check

The application includes a health check endpoint at `/health` that verifies:
- Database connectivity
- Redis connectivity
- Application status

Access it at: `http://your-server/health`

## Firewall Configuration

If using UFW firewall:

```bash
# Allow HTTP traffic
sudo ufw allow 80

# Allow SSH (if needed)
sudo ufw allow 22

# Enable firewall
sudo ufw enable
```

## Security Considerations

1. **Change default passwords** in production
2. **Use HTTPS** for production deployments
3. **Limit database access** to application only
4. **Regular security updates** for the server
5. **Monitor application logs** for suspicious activity

## Support

For issues and support:
- Check application logs: `docker-compose -f docker-compose.production.yml logs app`
- Visit the repository: https://github.com/assassincreed2k1/Hanaya-Shop
- Create an issue on GitHub
