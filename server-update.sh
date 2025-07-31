#!/bin/bash

# Hanaya Shop - Complete Update Script for Ubuntu Server
# Includes: Stop containers, Pull new images, Update DB, Restart with queue workers

set -e

APP_DIR="/opt/hanaya-shop"
BACKUP_DIR="/opt/hanaya-shop-backups"

echo "=== Hanaya Shop Complete Update Script ==="
echo "This will update application with new migrations and queue workers"

# Check if running from correct directory
if [ ! -d "$APP_DIR" ]; then
    echo "❌ Hanaya Shop not found at $APP_DIR"
    echo "Please ensure the application is deployed first."
    exit 1
fi

cd $APP_DIR

# Create backup directory
mkdir -p $BACKUP_DIR

echo "📦 Creating database backup..."
BACKUP_NAME="hanaya-backup-$(date +%Y%m%d-%H%M%S)"
sudo docker-compose exec -T db mysqldump -u root -pTrungnghia2703 hanaya_shop > "$BACKUP_DIR/$BACKUP_NAME.sql"
echo "✅ Database backup saved: $BACKUP_DIR/$BACKUP_NAME.sql"

echo "⏹️  Stopping all services..."
sudo docker-compose down

echo "🗑️  Cleaning up old images..."
sudo docker system prune -f

echo "📥 Pulling latest Docker images..."
sudo docker-compose pull

echo "🚀 Starting services with new images..."
sudo docker-compose up -d

echo "⏳ Waiting for services to be ready..."
sleep 45

echo "📊 Running database migrations..."
sudo docker-compose exec -T app php artisan migrate --force

echo "🧹 Optimizing Laravel application..."
sudo docker-compose exec -T app php artisan cache:clear
sudo docker-compose exec -T app php artisan config:cache
sudo docker-compose exec -T app php artisan route:cache
sudo docker-compose exec -T app php artisan view:cache

echo "🔐 Setting proper permissions..."
sudo docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
sudo docker-compose exec -T app chmod -R 775 /var/www/html/storage

echo "🏥 Performing health checks..."
sleep 15

# Check if application is healthy
if curl -f http://localhost/health > /dev/null 2>&1; then
    echo "✅ Application is healthy!"
else
    echo "⚠️  Application health check failed"
    echo "📋 Container status:"
    sudo docker-compose ps
    echo "📋 Application logs:"
    sudo docker-compose logs app | tail -20
fi

echo ""
echo "🎉 Update completed successfully!"
echo ""
echo "📋 New Features Added:"
echo "   - Queue workers (Laravel jobs processing)"
echo "   - Updated database schema (addresses, jobs tables)"
echo "   - Order address_id and message fields"
echo ""
echo "📋 Management Commands:"
echo "   - Check status: sudo docker-compose ps"
echo "   - View app logs: sudo docker-compose logs -f app"
echo "   - View worker logs: sudo docker-compose exec app supervisorctl tail -f laravel-worker:*"
echo "   - Check health: curl http://localhost/health"
echo "   - Website: http://$(curl -s ifconfig.me 2>/dev/null || echo 'YOUR_SERVER_IP')"
echo ""
