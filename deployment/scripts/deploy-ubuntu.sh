#!/bin/bash
#
# Hanaya Shop Deployment Script for Ubuntu
# This script follows the deployment guide and installs Hanaya Shop using Docker Images
#

set -e  # Exit on any error

# Configuration
PROJECT_NAME="hanaya-shop"
DOCKER_IMAGE="assassincreed2k1/hanaya-shop:latest"
COMPOSE_FILE="docker-compose.yml"
PROJECT_DIR="/opt/hanaya-shop"

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

if ! docker compose version &> /dev/null; then
    print_color $RED "Docker Compose v2 is not installed. Please install Docker Compose plugin first."
    exit 1
fi

print_color $GREEN "Prerequisites check passed!"

# Create deployment directory
print_color $YELLOW "Creating deployment directory..."
sudo mkdir -p "$PROJECT_DIR"
sudo chown -R $USER:$USER "$PROJECT_DIR"

# Navigate to project directory
cd "$PROJECT_DIR"
print_color $YELLOW "Changed to directory: $(pwd)"

# Download Docker Compose file from repository
print_color $YELLOW "Downloading docker-compose.yml from repository..."
curl -fsSL -o docker-compose.yml \
  https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deployment/docker-compose.prod.yml

print_color $GREEN "docker-compose.yml downloaded successfully!"

print_color $YELLOW "NOTE: Please edit docker-compose.yml to fill in required environment variables:"
print_color $YELLOW "  - DB_DATABASE (e.g. hanaya_shop)"
print_color $YELLOW "  - DB_USERNAME (e.g. hanaya_user)"  
print_color $YELLOW "  - DB_PASSWORD (your database password)"
print_color $YELLOW "  - MYSQL_ROOT_PASSWORD (MySQL root password)"
print_color $YELLOW "  - MYSQL_DATABASE (same as DB_DATABASE)"
print_color $YELLOW "  - MYSQL_USER (same as DB_USERNAME)"
print_color $YELLOW "  - MYSQL_PASSWORD (same as DB_PASSWORD)"
print_color $RED "Press Enter after editing the compose file, or Ctrl+C to exit..."
read -r

# Stop existing containers if running
print_color $YELLOW "Stopping existing containers (if any)..."
docker compose down --remove-orphans || true

# Remove old containers to ensure clean deployment
print_color $YELLOW "Removing old containers..."
docker rm -f hanaya-shop-app hanaya-shop-db hanaya-shop-redis 2>/dev/null || true

# Pull latest images
print_color $YELLOW "Pulling latest Docker images..."
docker compose pull

# Start the application
print_color $YELLOW "Starting Hanaya Shop application..."
docker compose up -d

# Wait for services to be ready
print_color $YELLOW "Waiting for services to start..."
sleep 30

# Check if services are running
print_color $YELLOW "Checking service status..."
docker compose ps

# Run first-time setup as per deployment guide
print_color $YELLOW "Running first-time setup..."
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan optimize

print_color $YELLOW "Starting queue worker for emails/notifications..."
docker compose exec -d app php artisan queue:work --queue=default --sleep=1 --tries=3

# Show final status
print_color $BLUE "==================================="
print_color $GREEN "  Deployment Completed Successfully!"
print_color $BLUE "==================================="

echo ""
print_color $GREEN "Application Status:"
docker compose ps

echo ""
print_color $GREEN "Application is now running at:"
print_color $GREEN "🌐 HTTP:  http://localhost"
print_color $GREEN "🌐 HTTP:  http://$(hostname -I | awk '{print $1}')"

echo ""
print_color $YELLOW "Useful commands:"
print_color $YELLOW "📋 View logs:           docker compose logs -f"
print_color $YELLOW "🔄 Restart services:    docker compose restart"
print_color $YELLOW "⏹️  Stop services:       docker compose down"
print_color $YELLOW "🗂️  Execute commands:    docker compose exec app bash"

echo ""
print_color $GREEN "Queue workers are running automatically!"
print_color $GREEN "Check queue status: docker compose exec app php artisan queue:work --once"

# Test HTTP access
echo ""
print_color $YELLOW "Testing HTTP access..."
if curl -f http://localhost/ &>/dev/null; then
    print_color $GREEN "✅ Application is accessible!"
else
    print_color $RED "❌ Application might not be ready yet. Check logs:"
    print_color $RED "   docker compose logs app"
fi
