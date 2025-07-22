#!/bin/bash

# Deployment script for Hanaya Shop
# Usage: ./deploy.sh [environment]

set -e

ENVIRONMENT=${1:-production}
PROJECT_NAME="hanaya-shop"
BACKUP_DIR="./backups"

echo "🌸 Deploying Hanaya Shop to $ENVIRONMENT environment..."

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database before deployment
echo "📦 Creating database backup..."
if docker ps | grep -q "${PROJECT_NAME}-db"; then
    docker exec ${PROJECT_NAME}-db mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > $BACKUP_DIR/backup_$(date +%Y%m%d_%H%M%S).sql
    echo "✅ Database backup created"
fi

# Pull latest changes from git
echo "📡 Pulling latest changes..."
git pull origin main

# Build and start containers
echo "🔨 Building and starting containers..."
docker-compose -f deployment/docker-compose.prod.yml down
docker-compose -f deployment/docker-compose.prod.yml build --no-cache
docker-compose -f deployment/docker-compose.prod.yml up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 30

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan migrate --force

# Seed database if needed (first deployment)
if [ "$2" = "--seed" ]; then
    echo "🌱 Seeding database..."
    docker-compose -f deployment/docker-compose.prod.yml exec app php artisan db:seed --force
fi

# Clear and cache everything
echo "🧹 Clearing and caching..."
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan optimize

# Create storage link
echo "🔗 Creating storage link..."
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan storage:link

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose -f deployment/docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html
docker-compose -f deployment/docker-compose.prod.yml exec app chmod -R 755 /var/www/html
docker-compose -f deployment/docker-compose.prod.yml exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Health check
echo "🏥 Running health check..."
sleep 10
if curl -f http://localhost/ > /dev/null 2>&1; then
    echo "✅ Application is healthy and running!"
    echo "🌐 Visit http://localhost to see your application"
else
    echo "❌ Health check failed!"
    echo "📋 Checking logs..."
    docker-compose -f deployment/docker-compose.prod.yml logs app
    exit 1
fi

# Cleanup old images
echo "🧹 Cleaning up old Docker images..."
docker image prune -f

echo "🎉 Deployment completed successfully!"
echo ""
echo "📊 Container Status:"
docker-compose -f deployment/docker-compose.prod.yml ps

echo ""
echo "📈 Quick Stats:"
echo "- Application: http://localhost"
echo "- Database: MySQL 8.0 (Port 3306)"
echo "- Redis: Port 6379"
echo "- Logs: docker-compose -f deployment/docker-compose.prod.yml logs -f"

echo ""
echo "🛠️ Useful Commands:"
echo "- View logs: docker-compose -f deployment/docker-compose.prod.yml logs -f app"
echo "- Access shell: docker-compose -f deployment/docker-compose.prod.yml exec app bash"
echo "- Stop: docker-compose -f deployment/docker-compose.prod.yml down"
echo "- Restart: docker-compose -f deployment/docker-compose.prod.yml restart"
