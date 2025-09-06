#!/bin/bash

# One-liner update command for Hanaya Shop
# Usage: curl -sSL https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deployment/scripts/one-liner-update.sh | bash

# Or locally: ./one-liner-update.sh

echo "🌸 Hanaya Shop One-liner Update"

# Change to deployment directory
cd /opt/hanaya-shop || {
    echo "❌ /opt/hanaya-shop directory not found. Please run deployment first."
    exit 1
}

# Quick update
docker compose pull && docker compose up -d && sleep 30 && docker compose exec app php artisan migrate --force && docker compose exec app php artisan optimize

echo "✅ Update completed!"
