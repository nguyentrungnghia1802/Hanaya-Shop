# 🚀 Production Deployment Checklist

## 📋 Pre-Deployment Requirements

### 1. **Server Environment**
```bash
# Ubuntu version
lsb_release -a

# PHP version (must match CI: 8.2)
php -v

# Required PHP extensions
php -m | grep -E "(curl|mbstring|zip|xml|gd|redis|mysql|bcmath|soap|intl|exif|iconv)"
```

### 2. **Directory Permissions**
```bash
# Create required directories
sudo mkdir -p storage/framework/cache/data
sudo mkdir -p storage/framework/sessions
sudo mkdir -p storage/framework/views
sudo mkdir -p storage/logs
sudo mkdir -p bootstrap/cache

# Set ownership (replace 'www-data' with your web server user)
sudo chown -R www-data:www-data storage bootstrap/cache

# Set permissions
sudo chmod -R 755 storage
sudo chmod -R 755 bootstrap/cache
```

### 3. **Laravel Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. **Database Setup**
```bash
# Run migrations
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --force
```

## 🔍 **Health Checks**

### 1. **Application Boot Test**
```bash
php artisan about
```

### 2. **Database Connection Test**
```bash
php artisan migrate:status
```

### 3. **Cache Operations Test**
```bash
# Test cache writing
php -r "file_put_contents('storage/logs/test.log', 'Test log entry');"
php -r "file_put_contents('storage/framework/cache/data/test.cache', 'Test cache');"

# Clean up
rm storage/logs/test.log
rm storage/framework/cache/data/test.cache
```

### 4. **View Compilation Test**
```bash
# Test view caching
php artisan view:cache
```

### 5. **Web Server Test**
```bash
# Test basic route (replace with your domain)
curl -I http://your-domain.com
```

## ⚠️ **Common Production Issues**

### 1. **Permission Issues**
- ❌ `storage/framework/views` not writable
- ✅ Solution: `sudo chmod -R 755 storage`

### 2. **Missing PHP Extensions**
- ❌ `gd` extension not installed
- ✅ Solution: `sudo apt install php8.2-gd`

### 3. **Cache Path Issues**
- ❌ "Please provide a valid cache path"
- ✅ Solution: Ensure `storage/framework/views` exists and is writable

### 4. **Environment Variables**
- ❌ `.env` file missing or incorrect
- ✅ Solution: Copy `.env.example` and configure properly

## 🚀 **Deployment Commands**

```bash
# Complete deployment script
#!/bin/bash
set -e

echo "🚀 Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Clear and cache
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan config:cache
php artisan view:cache

# Database
php artisan migrate --force

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# Restart services
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm

echo "✅ Deployment completed!"
```

## 📝 **Notes**
- Always test in staging environment first
- Keep database backups before migrations
- Monitor logs during deployment: `tail -f storage/logs/laravel.log`