#!/bin/bash

# Cleanup script for Hanaya Shop Laravel project
# This script removes unnecessary files and optimizes the project

echo "🧹 Cleaning up Hanaya Shop project..."

# Remove temporary files
echo "📁 Removing temporary files..."
find . -name "*.tmp" -type f -delete 2>/dev/null || true
find . -name "*.log" -type f -delete 2>/dev/null || true
find . -name ".DS_Store" -type f -delete 2>/dev/null || true
find . -name "Thumbs.db" -type f -delete 2>/dev/null || true

# Clear Laravel caches
echo "🚀 Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear application-specific caches
echo "🗂️ Clearing application caches..."
php artisan app:clear-cache

# Optimize for production (uncomment for production deployment)
# echo "⚡ Optimizing for production..."
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Remove development files (uncomment for production deployment)
# echo "🗑️ Removing development files..."
# rm -rf tests/
# rm -rf .git/
# rm phpunit.xml
# rm .env.example

echo "✅ Cleanup completed successfully!"
echo "📊 Project is now optimized and ready to use."
