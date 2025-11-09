#!/bin/bash
#
# Hanaya Shop Update Script for Ubuntu
# This script will update the Hanaya Shop application with the latest Docker image
#

set -e  # Exit on any error

# Configuration
PROJECT_NAME="hanaya-shop"
DOCKER_IMAGE="assassincreed2k1/hanaya-shop:latest"
COMPOSE_FILE="docker-compose.yml"

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

# Check if docker-compose.yml exists
if [ ! -f "$COMPOSE_FILE" ]; then
    print_color $RED "docker-compose.yml not found!"
    print_color $RED "Please run deploy-ubuntu.sh first."
    exit 1
fi

print_color $YELLOW "Pulling latest Docker image..."
docker compose pull

print_color $YELLOW "Updating to latest version..."
docker compose up -d

print_color $YELLOW "Waiting for application to start..."
sleep 30

print_color $YELLOW "Running database migrations..."
docker compose exec app php artisan migrate --force

print_color $YELLOW "Optimizing application..."
docker compose exec app php artisan optimize

# Show final status
print_color $BLUE "==================================="
print_color $GREEN "  Update Completed Successfully!"
print_color $BLUE "==================================="

echo ""
print_color $GREEN "Application Status:"
docker compose ps

# Test HTTP access
echo ""
print_color $YELLOW "Testing HTTP access..."
if curl -f http://localhost/ &>/dev/null; then
    print_color $GREEN "✅ Application is running with latest version!"
else
    print_color $RED "❌ Application might not be ready yet. Check logs:"
    print_color $RED "   docker compose logs app"
fi

echo ""
print_color $GREEN "Application is now running at:"
print_color $GREEN "🌐 HTTP:  http://localhost"
print_color $GREEN "🌐 HTTP:  http://$(hostname -I | awk '{print $1}')"
