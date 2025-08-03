# 🚀 HANAYA SHOP - DEPLOYMENT & UPDATE COMMANDS

## 📦 Docker Image Info
- **Repository**: assassincreed2k1/hanaya-shop
- **Tag**: latest  
- **Size**: 758MB
- **Build Date**: $(date)

## 🔄 BUILD & PUSH COMMANDS (Đã chạy)

```bash
# Build image
docker build -t assassincreed2k1/hanaya-shop:latest .

# Push to Docker Hub
docker push assassincreed2k1/hanaya-shop:latest
```

## 🖥️ UBUNTU SERVER UPDATE COMMANDS

### 🎯 Method 1: Quick Update (Recommended)
```bash
cd ~/hanayashop && ./quick-update.sh
```

### 🎯 Method 2: Manual Update  
```bash
cd ~/hanayashop && ./update-ubuntu.sh
```

### 🎯 Method 3: One-liner Update
```bash
cd ~/hanayashop && docker-compose -f docker-compose.production.yml pull app && docker-compose -f docker-compose.production.yml up -d app
```

### 🎯 Method 4: Complete Update Steps
```bash
# 1. Go to project directory
cd ~/hanayashop

# 2. Put app in maintenance mode
docker-compose -f docker-compose.production.yml exec -T app php artisan down

# 3. Pull latest image
docker-compose -f docker-compose.production.yml pull app

# 4. Restart container
docker-compose -f docker-compose.production.yml stop app
docker-compose -f docker-compose.production.yml rm -f app  
docker-compose -f docker-compose.production.yml up -d app

# 5. Wait for startup
sleep 20

# 6. Run migrations (only if needed)
MIGRATION_PENDING=$(docker-compose -f docker-compose.production.yml exec -T app php artisan migrate:status | grep -c "No")
if [ "$MIGRATION_PENDING" -gt 0 ]; then
    docker-compose -f docker-compose.production.yml exec -T app php artisan migrate --force
fi

# 7. Clear caches
docker-compose -f docker-compose.production.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan route:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan view:clear
docker-compose -f docker-compose.production.yml exec -T app php artisan cache:clear

# 8. Set permissions
docker-compose -f docker-compose.production.yml exec -T app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data storage bootstrap/cache

# 9. Optimize application
docker-compose -f docker-compose.production.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.production.yml exec -T app php artisan view:cache

# 10. Bring app back online
docker-compose -f docker-compose.production.yml exec -T app php artisan up
```

## 🔍 CHECK STATUS COMMANDS

```bash
# Check containers
docker-compose -f docker-compose.production.yml ps

# Check logs  
docker-compose -f docker-compose.production.yml logs app

# Test application
curl -f http://localhost/health

# Check application status
docker-compose -f docker-compose.production.yml exec -T app php artisan --version
```

## 🆘 TROUBLESHOOTING COMMANDS

```bash
# Full restart
docker-compose -f docker-compose.production.yml down
docker-compose -f docker-compose.production.yml up -d

# Clear all caches
docker-compose -f docker-compose.production.yml exec -T app php artisan optimize:clear

# Fix permissions
docker-compose -f docker-compose.production.yml exec -T app chown -R www-data:www-data /var/www/html

# Check disk space
df -h
docker system df
```

## 🎊 QUICK COPY-PASTE FOR UBUNTU

**For immediate update:**
```bash
cd ~/hanayashop && ./update-ubuntu.sh
```

**For emergency restart:**
```bash
cd ~/hanayashop && docker-compose -f docker-compose.production.yml restart app
```

---

🎉 **Image đã được build và push thành công!**
🚀 **Sẵn sàng để update trên Ubuntu server!**
