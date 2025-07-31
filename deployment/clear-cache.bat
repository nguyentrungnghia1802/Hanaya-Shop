@echo off
echo 🔄 Clearing all caches after CSP update...

REM Clear Laravel caches trong container
docker compose -f docker-compose.prod.yml exec app php artisan config:clear
docker compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker compose -f docker-compose.prod.yml exec app php artisan route:clear
docker compose -f docker-compose.prod.yml exec app php artisan view:clear

REM Rebuild optimized caches
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache

echo ✅ Cache cleared and optimized!
echo 🌐 Application should now be CSP-compliant
echo 📍 Access your app at: http://localhost

pause
