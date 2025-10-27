#!/bin/bash

# Update dependencies and rebuild assets script for CSP fixes
echo "🔧 Updating Hanaya Shop for CSP compliance..."

# Update npm dependencies
echo "📦 Updating npm dependencies..."
npm install

# Rebuild frontend assets
echo "🏗️ Building frontend assets..."
npm run build

# Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize Laravel for production
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ CSP compliance update completed!"
echo "🌐 Alpine.js components are now CSP-compliant"
echo "🔒 Security headers have been updated"
echo ""
echo "Next steps:"
echo "1. Test the application thoroughly"
echo "2. Deploy using: docker compose -f deployment/docker-compose.prod.yml up -d --build"
echo "3. Monitor for any console errors"
