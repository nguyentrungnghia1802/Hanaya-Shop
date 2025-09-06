#!/bin/bash

# General Deployment script for Hanaya Shop
# Usage: ./deploy.sh [environment]
# This script follows the DEPLOYMENT_GUIDE

set -e

ENVIRONMENT=${1:-production}
PROJECT_NAME="hanaya-shop"
BACKUP_DIR="./backups"
PROJECT_DIR="/opt/hanaya-shop"

echo "🌸 Deploying Hanaya Shop to $ENVIRONMENT environment..."

# Create deployment directory
echo "📁 Creating deployment directory..."
sudo mkdir -p $PROJECT_DIR
sudo chown -R $USER:$USER $PROJECT_DIR
cd $PROJECT_DIR

# Create backup directory
mkdir -p $BACKUP_DIR

# Download Docker Compose file
echo "📡 Downloading docker-compose.yml..."
curl -fsSL -o docker-compose.yml \
  https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deployment/docker-compose.prod.yml

echo "⚠️  Please edit docker-compose.yml to fill in environment variables before continuing..."
echo "Press Enter after editing, or Ctrl+C to exit..."
read -r

# Backup database before deployment
echo "📦 Creating database backup..."
if docker ps | grep -q "${PROJECT_NAME}-db"; then
    docker compose exec db mysqldump -u root -p${DB_ROOT_PASSWORD:-} ${DB_DATABASE:-hanaya_shop} > $BACKUP_DIR/backup_$(date +%Y%m%d_%H%M%S).sql || true
    echo "✅ Database backup created"
fi

# Build and start containers
echo "🔨 Pulling and starting containers..."
docker compose pull
docker compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 30

# Run first-time setup following deployment guide
echo "🔑 Generating application key..."
docker compose exec app php artisan key:generate --force

# Run database migrations
echo "🗄️ Running database migrations..."
docker compose exec app php artisan migrate --force

# Seed database if needed (first deployment)
if [ "$2" = "--seed" ]; then
    echo "🌱 Seeding database..."
    docker compose exec app php artisan db:seed --force
fi

# Optimize application
echo "🚀 Optimizing application..."
docker compose exec app php artisan optimize

# Start queue worker
echo "� Starting queue worker..."
docker compose exec -d app php artisan queue:work --queue=default --sleep=1 --tries=3

# Health check
echo "🏥 Running health check..."
sleep 10
if curl -f http://localhost/ > /dev/null 2>&1; then
    echo "✅ Application is healthy and running!"
    echo "🌐 Visit http://localhost to see your application"
else
    echo "❌ Health check failed!"
    echo "📋 Checking logs..."
    docker compose logs app
    exit 1
fi

# Cleanup old images
echo "🧹 Cleaning up old Docker images..."
docker image prune -f

echo "🎉 Deployment completed successfully!"
echo ""
echo "📊 Container Status:"
docker compose ps

echo ""
echo "📈 Quick Stats:"
echo "- Application: http://localhost"
echo "- Database: MySQL 8.0 (Port 3306)"
echo "- Redis: Port 6379"
echo "- Logs: docker compose logs -f"

echo ""
echo "🛠️ Useful Commands:"
echo "- View logs: docker compose logs -f app"
echo "- Access shell: docker compose exec app bash"
echo "- Stop: docker compose down"
echo "- Restart: docker compose restart"
