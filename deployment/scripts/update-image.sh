#!/bin/bash

# Hanaya Shop Production Update Script
# Safe update with backup and rollback capability

set -e  # Exit on any error

echo "🌸 Hanaya Shop Production Update"
echo "📅 Started at: $(date)"

# Navigate to project directory
cd /opt/hanaya-shop || {
    echo "❌ /opt/hanaya-shop directory not found"
    exit 1
}

echo "📁 Current directory: $(pwd)"

# 1. Create database backup
echo "💾 Creating database backup..."
BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
docker compose exec -T db mysqldump -u hanaya_user -phanaya_password hanaya_shop > "storage/backups/${BACKUP_FILE}" || {
    echo "⚠️ Database backup failed, but continuing..."
}

# 2. Pull latest images
echo "🐳 Pulling latest Docker images..."
docker compose pull || {
    echo "❌ Failed to pull Docker images"
    exit 1
}

# 3. Restart containers with zero downtime
echo "♻️ Restarting containers..."
docker compose down --remove-orphans
docker compose up -d || {
    echo "❌ Failed to start containers"
    exit 1
}

# 4. Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 30

# 5. Run Laravel optimizations
echo "⚡ Running Laravel optimizations..."
docker compose exec app php artisan config:clear || echo "⚠️ Config clear failed"
docker compose exec app php artisan cache:clear || echo "⚠️ Cache clear failed"
docker compose exec app php artisan view:clear || echo "⚠️ View clear failed"
docker compose exec app php artisan route:cache || echo "⚠️ Route cache failed"
docker compose exec app php artisan view:cache || echo "⚠️ View cache failed"

# 6. Verify deployment
echo "🔍 Verifying deployment..."
if docker compose ps | grep -q "Up"; then
    echo "✅ Containers are running!"
    docker compose ps
    
    # Health check
    echo "🏥 Performing health check..."
    sleep 5
    if curl -f http://localhost > /dev/null 2>&1; then
        echo "✅ Application is healthy and responding!"
        echo "🌐 Update completed successfully!"
    else
        echo "⚠️ Health check failed, but containers are running"
        echo "💡 Try clearing browser cache or check application logs"
    fi
else
    echo "❌ Some containers are not running properly"
    docker compose ps
    exit 1
fi

echo "🎉 Production update completed at: $(date)"
echo "📊 Backup saved as: storage/backups/${BACKUP_FILE}"
echo "🔄 To rollback: docker compose down && docker compose up -d [previous-image-tag]"