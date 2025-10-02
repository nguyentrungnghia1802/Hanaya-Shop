#!/bin/bash
#
# Clear Cache Script for Hanaya Shop
# This script clears all application caches
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
print_color $BLUE "  Hanaya Shop Cache Clear"
print_color $BLUE "========================================"

# Check if we're in the deployment directory or change to it
if [ ! -f "docker-compose.yml" ]; then
    if [ -d "/opt/hanaya-shop" ]; then
        cd /opt/hanaya-shop
        print_color $YELLOW "Changed to /opt/hanaya-shop directory"
    else
        print_color $RED "docker-compose.yml not found and /opt/hanaya-shop doesn't exist!"
        exit 1
    fi
fi

print_color $YELLOW "Clearing all caches..."

# Clear Laravel caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Optionally rebuild optimized caches
if [ "$1" = "--optimize" ]; then
    print_color $YELLOW "Rebuilding optimized caches..."
    docker compose exec app php artisan config:cache
    docker compose exec app php artisan route:cache
    docker compose exec app php artisan view:cache
    print_color $GREEN "✅ Caches rebuilt and optimized!"
else
    print_color $GREEN "✅ All caches cleared!"
fi

print_color $GREEN "🌐 Application ready at: http://localhost"
#!/bin/bash
#
# Clear Cache Script for Hanaya Shop
# This script clears all application caches
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
print_color $BLUE "  Hanaya Shop Cache Clear"
print_color $BLUE "========================================"

# Check if we're in the deployment directory or change to it
if [ ! -f "docker-compose.yml" ]; then
    if [ -d "/opt/hanaya-shop" ]; then
        cd /opt/hanaya-shop
        print_color $YELLOW "Changed to /opt/hanaya-shop directory"
    else
        print_color $RED "docker-compose.yml not found and /opt/hanaya-shop doesn't exist!"
        exit 1
    fi
fi

print_color $YELLOW "Clearing all caches..."

# Clear Laravel caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Optionally rebuild optimized caches
if [ "$1" = "--optimize" ]; then
    print_color $YELLOW "Rebuilding optimized caches..."
    docker compose exec app php artisan config:cache
    docker compose exec app php artisan route:cache
    docker compose exec app php artisan view:cache
    print_color $GREEN "✅ Caches rebuilt and optimized!"
else
    print_color $GREEN "✅ All caches cleared!"
fi

print_color $GREEN "🌐 Application ready at: http://localhost"
