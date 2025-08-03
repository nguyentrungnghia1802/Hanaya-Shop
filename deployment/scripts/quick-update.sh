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

# Ensure we're in the right directory
if [ ! -f "docker-compose.production.yml" ]; then
    print_color $RED "docker-compose.production.yml not found!"
    print_color $RED "Please make sure you're in the correct directory."
    exit 1
fi

# Run the main update script
if [ -f "update-ubuntu.sh" ]; then
    print_color $YELLOW "Running update script..."
    chmod +x update-ubuntu.sh
    ./update-ubuntu.sh
else
    print_color $RED "update-ubuntu.sh not found!"
    print_color $RED "Please download the update script first."
    exit 1
fi

print_color $GREEN "✅ Update completed successfully!"
print_color $GREEN "🌐 Your application is now running with the latest version!"
