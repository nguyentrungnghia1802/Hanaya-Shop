#!/bin/bash

# Hanaya Shop Update Script
# Run this script to update the application to the latest version

set -e

APP_DIR="/opt/hanaya-shop"
BACKUP_DIR="/opt/hanaya-shop-backups"

echo "=== Hanaya Shop Update Script ==="

# Check if application directory exists
if [ ! -d "$APP_DIR" ]; then
    echo "❌ Hanaya Shop not found at $APP_DIR"
    echo "Please run the deployment script first."
    exit 1
fi

cd $APP_DIR

# Create backup directory
mkdir -p $BACKUP_DIR

# Create backup
echo "📦 Creating backup..."
BACKUP_NAME="hanaya-backup-$(date +%Y%m%d-%H%M%S)"
docker-compose exec -T db mysqldump -u root -pTrungnghia2703 hanaya_shop > "$BACKUP_DIR/$BACKUP_NAME.sql"
echo "✅ Database backup created: $BACKUP_DIR/$BACKUP_NAME.sql"

# Pull latest images
echo "📥 Pulling latest Docker images..."
docker-compose pull

# Stop services
echo "⏹️  Stopping services..."
docker-compose down

# Start services with new images
echo "🚀 Starting services with updated images..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Run migrations (if any)
echo "📊 Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Clear and optimize caches
echo "🧹 Clearing and optimizing caches..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# Check application health
echo "🏥 Checking application health..."
sleep 10

if curl -f http://localhost/health > /dev/null 2>&1; then
    echo "✅ Application is healthy and running!"
else
    echo "⚠️  Application health check failed. Please check logs:"
    echo "   docker-compose logs app"
fi

echo ""
echo "🎉 Update completed successfully!"
echo ""
echo "📋 Post-update commands:"
echo "   - Check status: docker-compose ps"
echo "   - View logs: docker-compose logs -f app"
echo "   - Check health: curl http://localhost/health"
echo ""
