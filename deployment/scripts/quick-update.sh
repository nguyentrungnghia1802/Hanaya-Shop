#!/bin/bash
#
# Quick Update Command for Hanaya Shop on Ubuntu
# This script pulls and deploys the latest version
#

set -e  # Exit on any error

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

print_color $BLUE "========================================"
print_color $BLUE "  Hanaya Shop Quick Update"
print_color $BLUE "========================================"

# Check if we're in the deployment directory or change to it
if [ ! -f "docker-compose.yml" ]; then
    if [ -d "/opt/hanaya-shop" ]; then
        cd /opt/hanaya-shop
        print_color $YELLOW "Changed to /opt/hanaya-shop directory"
    else
        print_color $RED "docker-compose.yml not found and /opt/hanaya-shop doesn't exist!"
        print_color $RED "Please run deploy-ubuntu.sh first."
        exit 1
    fi
fi

# Pull and update
print_color $YELLOW "Pulling latest image and updating..."
docker compose pull
docker compose up -d

print_color $YELLOW "Waiting for application to be ready..."
sleep 30

print_color $YELLOW "Running database migrations..."
docker compose exec app php artisan migrate --force

print_color $YELLOW "Optimizing application..."
docker compose exec app php artisan optimize

print_color $GREEN "✅ Update completed successfully!"
print_color $GREEN "🌐 Your application is now running with the latest version!"

echo ""
print_color $GREEN "Application Status:"
docker compose ps
