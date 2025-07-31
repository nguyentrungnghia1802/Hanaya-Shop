#!/bin/bash

# Hanaya Shop - Fix Docker Issues and Deploy
# Run this script to fix common Docker issues and deploy the application

set -e

echo "=== Hanaya Shop - Docker Fix and Deploy ==="

# Stop any running containers
echo "🛑 Stopping any running containers..."
sudo docker-compose down 2>/dev/null || true
sudo docker stop $(sudo docker ps -aq) 2>/dev/null || true

# Clean up Docker system
echo "🧹 Cleaning up Docker system..."
sudo docker system prune -f
sudo docker volume prune -f
sudo docker network prune -f

# Restart Docker service
echo "🔄 Restarting Docker service..."
sudo systemctl restart docker
sleep 5

# Check Docker status
echo "✅ Checking Docker status..."
sudo systemctl status docker --no-pager

# Remove old docker-compose.yml if exists
if [ -f "docker-compose.yml" ]; then
    echo "🗑️ Removing old docker-compose.yml..."
    sudo rm docker-compose.yml
fi

# Create optimized docker-compose.yml
echo "📝 Creating optimized docker-compose.yml..."
sudo cat > docker-compose.yml << 'EOF'
services:
  app:
    image: assassincreed2k1/hanaya-shop:latest
    container_name: hanaya-shop-app
    ports:
      - "80:80"
      - "443:443"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_KEY=base64:WKmU6VSLy60iTtk4lEcsl5etRDRv6AQbEEf8dOitxy4=
      - APP_URL=http://localhost
      - DB_HOST=db
      - DB_DATABASE=hanaya_shop
      - DB_USERNAME=hanaya_user
      - DB_PASSWORD=Trungnghia2703
      - REDIS_HOST=redis
      - CACHE_DRIVER=redis
      - SESSION_DRIVER=redis
      - QUEUE_CONNECTION=redis
      - MAIL_MAILER=smtp
      - MAIL_HOST=smtp.gmail.com
      - MAIL_PORT=587
      - MAIL_USERNAME=assassincreed2k1@gmail.com
      - MAIL_PASSWORD=tijrvguflmbctaba
      - MAIL_ENCRYPTION=tls
      - MAIL_FROM_ADDRESS=assassincreed2k1@gmail.com
      - MAIL_FROM_NAME="Hanaya Shop"
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
      start_period: 60s

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
    command: --default-authentication-plugin=mysql_native_password --max_connections=200

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
    command: redis-server --appendonly yes --maxmemory 256mb --maxmemory-policy allkeys-lru

volumes:
  db_data:
    driver: local
  redis_data:
    driver: local
  storage_data:
    driver: local
  logs_data:
    driver: local

networks:
  hanaya-network:
    driver: bridge
EOF

echo "📥 Pulling images one by one to avoid conflicts..."

# Pull images individually with retry logic
pull_image() {
    local image=$1
    local max_retries=3
    local retry=0
    
    while [ $retry -lt $max_retries ]; do
        echo "📦 Pulling $image (attempt $((retry + 1))/$max_retries)..."
        if sudo docker pull $image; then
            echo "✅ Successfully pulled $image"
            return 0
        else
            echo "❌ Failed to pull $image, retrying..."
            retry=$((retry + 1))
            sleep 10
        fi
    done
    
    echo "❌ Failed to pull $image after $max_retries attempts"
    return 1
}

# Pull each image separately
pull_image "redis:7-alpine"
pull_image "mysql:8.0"
pull_image "assassincreed2k1/hanaya-shop:latest"

echo "🚀 Starting services..."
sudo docker-compose up -d

echo "⏳ Waiting for services to start..."
sleep 30

echo "🔍 Checking service status..."
sudo docker-compose ps

echo "📊 Waiting for database to be ready..."
timeout=60
counter=0
while ! sudo docker-compose exec -T db mysql -u root -pTrungnghia2703 -e "SELECT 1" >/dev/null 2>&1; do
    if [ $counter -ge $timeout ]; then
        echo "❌ Database startup timeout"
        sudo docker-compose logs db
        exit 1
    fi
    echo "⏳ Waiting for database... ($counter/$timeout)"
    sleep 5
    counter=$((counter + 5))
done

echo "✅ Database is ready!"

echo "🗃️ Running database migrations..."
sudo docker-compose exec -T app php artisan migrate --force

echo "🌱 Seeding database..."
sudo docker-compose exec -T app php artisan db:seed --force

echo "⚡ Optimizing application..."
sudo docker-compose exec -T app php artisan config:cache
sudo docker-compose exec -T app php artisan route:cache
sudo docker-compose exec -T app php artisan view:cache

echo "🔐 Setting permissions..."
sudo docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
sudo docker-compose exec -T app chmod -R 775 /var/www/html/storage

echo "🔥 Configuring firewall..."
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw --force enable

echo ""
echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Application Info:"
echo "   - URL: http://$(curl -s ifconfig.me 2>/dev/null || echo 'YOUR_SERVER_IP')"
echo "   - Health: http://$(curl -s ifconfig.me 2>/dev/null || echo 'YOUR_SERVER_IP')/health"
echo "   - Admin: http://$(curl -s ifconfig.me 2>/dev/null || echo 'YOUR_SERVER_IP')/admin"
echo ""
echo "📋 Management Commands:"
echo "   - Status: sudo docker-compose ps"
echo "   - Logs: sudo docker-compose logs -f app"
echo "   - Restart: sudo docker-compose restart"
echo ""

# Final health check
echo "🏥 Performing health check..."
if sudo docker-compose exec -T app curl -f http://localhost/health >/dev/null 2>&1; then
    echo "✅ Application is healthy and running!"
else
    echo "⚠️ Application health check failed. Checking logs..."
    sudo docker-compose logs app | tail -20
fi

echo "✅ Setup complete!"
