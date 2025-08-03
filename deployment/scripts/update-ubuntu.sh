#!/bin/bash
#
# Hanaya Shop Update Script for Ubuntu
# This script will update the Hanaya Shop application with the latest Docker image
#

set -e  # Exit on any error

# Configuration
PROJECT_NAME="hanaya-shop"
DOCKER_IMAGE="assassincreed2k1/hanaya-shop:latest"
COMPOSE_FILE="docker-compose.production.yml"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_color() {
    echo -e "${1}${2}${NC}"
}

print_color $BLUE "==================================="
print_color $BLUE "  Hanaya Shop Update Script"
print_color $BLUE "==================================="

# Check if docker-compose.production.yml exists
if [ ! -f "$COMPOSE_FILE" ]; then
    print_color $RED "docker-compose.production.yml not found!"
    print_color $RED "Please run deploy-ubuntu.sh first."
    exit 1
fi

print_color $YELLOW "Creating backup..."
docker-compose -f docker-compose.production.yml exec -T app php artisan down || true

print_color $YELLOW "Pulling latest Docker image..."
docker-compose -f docker-compose.production.yml pull app

print_color $YELLOW "Stopping application container..."
docker-compose -f docker-compose.production.yml stop app

print_color $YELLOW "Removing old application container..."
docker-compose -f docker-compose.production.yml rm -f app

print_color $YELLOW "Starting updated application..."
docker-compose -f docker-compose.production.yml up -d app

print_color $YELLOW "Waiting for application to start..."
sleep 20

print_color $YELLOW "Running database migrations..."
MIGRATION_PENDING=$(docker-compose -f docker-compose.production.yml exec -T app php artisan migrate:status | grep -c "No")
if [ "$MIGRATION_PENDING" -gt 0 ]; then
    print_color $YELLOW "Running database migrations... ($MIGRATION_PENDING pending)"
    docker-compose -f docker-compose.production.yml exec -T app php artisan migrate --force
else
    print_color $GREEN "No new migrations to run. Skipping migrate."
fi

print_color $YELLOW "Clearing caches..."
docker-compose -f docker-compose.production.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan route:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan view:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan cache:clear

print_color $YELLOW "Ensuring proper permissions..."
docker-compose -f docker-compose.production.yml exec -T app mkdir -p storage/framework/views
docker-compose -f docker-compose.production.yml exec -T app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data storage bootstrap/cache

print_color $YELLOW "Optimizing application..."
docker-compose -f docker-compose.production.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan view:cache

print_color $YELLOW "Bringing application back online..."
docker-compose -f docker-compose.production.yml exec -T app php artisan up

# Show final status
print_color $BLUE "==================================="
print_color $GREEN "  Update Completed Successfully!"
print_color $BLUE "==================================="

echo ""
print_color $GREEN "Application Status:"
docker-compose -f docker-compose.production.yml ps

# Test HTTP access
echo ""
print_color $YELLOW "Testing HTTP access..."
if curl -f http://localhost/health &>/dev/null; then
    print_color $GREEN "✅ Health check passed - Application is running with latest version!"
else
    print_color $RED "❌ Health check failed - Please check logs:"
    print_color $RED "   docker-compose -f docker-compose.production.yml logs app"
fi

echo ""
print_color $GREEN "Application is now running at:"
print_color $GREEN "🌐 HTTP:  http://localhost"
print_color $GREEN "🌐 HTTP:  http://$(hostname -I | awk '{print $1}')"
