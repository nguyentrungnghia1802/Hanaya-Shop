#!/bin/bash

# Import SQL backup to Ubuntu server script
# Usage: ./import-sql.sh [sql_file_path]

set -e

SQL_FILE=${1:-"hanaya_shop_backup.sql"}
APP_DIR="/opt/hanaya-shop"

echo "=== Hanaya Shop SQL Import Script ==="
echo "SQL File: $SQL_FILE"

# Check if running from correct directory
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Please run this script from $APP_DIR directory"
    exit 1
fi

# Check if SQL file exists
if [ ! -f "$SQL_FILE" ]; then
    echo "❌ SQL file not found: $SQL_FILE"
    echo "Please upload your SQL file to $APP_DIR first:"
    echo "scp your_file.sql root@server_ip:$APP_DIR/"
    exit 1
fi

# Check if containers are running
if ! sudo docker-compose ps | grep -q "hanaya-shop-db.*Up"; then
    echo "❌ Database container is not running"
    echo "Please start the application first: sudo docker-compose up -d"
    exit 1
fi

# Create backup of current database
echo "📦 Creating backup of current database..."
BACKUP_NAME="backup-before-import-$(date +%Y%m%d-%H%M%S).sql"
sudo docker-compose exec -T db mysqldump -u root -pTrungnghia2703 hanaya_shop > "$BACKUP_NAME" 2>/dev/null || true
echo "✅ Current database backed up to: $BACKUP_NAME"

# Confirm import
echo ""
echo "⚠️  WARNING: This will replace ALL data in the database!"
echo "   - Current data will be backed up to: $BACKUP_NAME"
echo "   - New data will be imported from: $SQL_FILE"
echo ""
read -p "Continue? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Import cancelled"
    exit 1
fi

# Stop application to prevent connections during import
echo "⏸️  Temporarily stopping application..."
sudo docker-compose stop app

# Drop and recreate database
echo "🗑️  Dropping existing database..."
sudo docker-compose exec -T db mysql -u root -pTrungnghia2703 -e "DROP DATABASE IF EXISTS hanaya_shop;"

echo "🆕 Creating new database..."
sudo docker-compose exec -T db mysql -u root -pTrungnghia2703 -e "CREATE DATABASE hanaya_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import SQL file
echo "📥 Importing SQL file..."
if sudo docker-compose exec -T db mysql -u root -pTrungnghia2703 hanaya_shop < "$SQL_FILE"; then
    echo "✅ SQL import completed successfully!"
else
    echo "❌ SQL import failed!"
    echo "🔄 Restoring from backup..."
    sudo docker-compose exec -T db mysql -u root -pTrungnghia2703 -e "DROP DATABASE IF EXISTS hanaya_shop; CREATE DATABASE hanaya_shop;"
    sudo docker-compose exec -T db mysql -u root -pTrungnghia2703 hanaya_shop < "$BACKUP_NAME"
    echo "✅ Database restored from backup"
    exit 1
fi

# Start application
echo "🚀 Starting application..."
sudo docker-compose start app

# Wait for application to be ready
echo "⏳ Waiting for application to be ready..."
sleep 10

# Run Laravel optimizations
echo "⚡ Running Laravel optimizations..."
sudo docker-compose exec -T app php artisan config:cache
sudo docker-compose exec -T app php artisan route:cache
sudo docker-compose exec -T app php artisan view:cache

# Check application health
echo "🏥 Checking application health..."
if sudo docker-compose exec -T app curl -f http://localhost/health >/dev/null 2>&1; then
    echo "✅ Application is healthy and running!"
else
    echo "⚠️  Application health check failed. Please check logs:"
    echo "   sudo docker-compose logs app"
fi

echo ""
echo "🎉 SQL import completed successfully!"
echo ""
echo "📋 Summary:"
echo "   - Backup saved: $BACKUP_NAME"
echo "   - Data imported from: $SQL_FILE"
echo "   - Application status: $(sudo docker-compose ps app --format 'table {{.Status}}')"
echo ""
echo "📋 Next steps:"
echo "   - Check website: http://$(curl -s ifconfig.me 2>/dev/null || echo 'YOUR_SERVER_IP')"
echo "   - View logs: sudo docker-compose logs -f app"
echo ""
