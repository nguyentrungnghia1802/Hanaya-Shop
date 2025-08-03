#!/bin/bash
#
# Hanaya Shop Deployment Script for Ubuntu
# This script will deploy the Hanaya Shop application using Docker Compose
#

set -e  # Exit on any error

# Configuration
PROJECT_NAME="hanaya-shop"
DOCKER_IMAGE="assassincreed2k1/hanaya-shop:latest"
COMPOSE_FILE="docker-compose.production.yml"
PROJECT_DIR="."

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
print_color $BLUE "  Hanaya Shop Deployment Script"
print_color $BLUE "==================================="

# Check if Docker and Docker Compose are installed
print_color $YELLOW "Checking prerequisites..."
if ! command -v docker &> /dev/null; then
    print_color $RED "Docker is not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_color $RED "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

print_color $GREEN "Prerequisites check passed!"

# Navigate to project directory
if [ ! -d "$PROJECT_DIR" ]; then
    print_color $RED "Directory $PROJECT_DIR does not exist!"
    exit 1
fi

cd "$PROJECT_DIR"
print_color $YELLOW "Changed to directory: $(pwd)"

# Create docker-compose.production.yml file
print_color $YELLOW "Creating docker-compose.production.yml..."
cat > docker-compose.production.yml << 'EOF'
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

print_color $GREEN "docker-compose.production.yml created successfully!"

# Stop existing containers if running
print_color $YELLOW "Stopping existing containers (if any)..."
docker-compose -f docker-compose.production.yml down --remove-orphans || true

# Remove old containers to ensure clean deployment
print_color $YELLOW "Removing old containers..."
docker rm -f hanaya-shop-app hanaya-shop-db hanaya-shop-redis 2>/dev/null || true

# Pull latest images
print_color $YELLOW "Pulling latest Docker images..."
docker-compose -f docker-compose.production.yml pull

# Start the application
print_color $YELLOW "Starting Hanaya Shop application..."
docker-compose -f docker-compose.production.yml up -d

# Wait for services to be ready
print_color $YELLOW "Waiting for services to start..."
sleep 15

# Check if services are running
print_color $YELLOW "Checking service status..."
docker-compose -f docker-compose.production.yml ps

# Wait for database to be ready and run migrations
print_color $YELLOW "Waiting for database to be ready..."
sleep 30

print_color $YELLOW "Running database migrations..."
docker-compose -f docker-compose.production.yml exec -T app php artisan migrate --force

print_color $YELLOW "Fixing cache and permissions..."
docker-compose -f docker-compose.production.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan route:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan view:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan cache:clear

# Create required directories and set permissions
print_color $YELLOW "Creating storage directories and setting permissions..."
docker-compose -f docker-compose.production.yml exec -T app mkdir -p storage/framework/views
docker-compose -f docker-compose.production.yml exec -T app mkdir -p storage/framework/cache
docker-compose -f docker-compose.production.yml exec -T app mkdir -p storage/framework/sessions
docker-compose -f docker-compose.production.yml exec -T app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data storage bootstrap/cache

print_color $YELLOW "Clearing and optimizing cache..."
docker-compose -f docker-compose.production.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan view:cache

# Show final status
print_color $BLUE "==================================="
print_color $GREEN "  Deployment Completed Successfully!"
print_color $BLUE "==================================="

echo ""
print_color $GREEN "Application Status:"
docker-compose -f docker-compose.production.yml ps

echo ""
print_color $GREEN "Application is now running at:"
print_color $GREEN "🌐 HTTP:  http://localhost"
print_color $GREEN "🌐 HTTP:  http://$(hostname -I | awk '{print $1}')"

echo ""
print_color $YELLOW "Useful commands:"
print_color $YELLOW "📋 View logs:           docker-compose -f docker-compose.production.yml logs -f"
print_color $YELLOW "🔄 Restart services:    docker-compose -f docker-compose.production.yml restart"
print_color $YELLOW "⏹️  Stop services:       docker-compose -f docker-compose.production.yml down"
print_color $YELLOW "🗂️  Execute commands:    docker-compose -f docker-compose.production.yml exec app bash"

echo ""
print_color $GREEN "Queue workers are running automatically via Supervisor!"
print_color $GREEN "Check queue status: docker-compose -f docker-compose.production.yml exec app php artisan queue:work --once"

# Test HTTP access
echo ""
print_color $YELLOW "Testing HTTP access..."
if curl -f http://localhost/health &>/dev/null; then
    print_color $GREEN "✅ Health check passed - Application is accessible!"
else
    print_color $RED "❌ Health check failed - Please check logs:"
    print_color $RED "   docker-compose -f docker-compose.production.yml logs app"
fi
