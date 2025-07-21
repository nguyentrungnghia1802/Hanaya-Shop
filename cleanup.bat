@echo off
echo 🧹 Cleaning up Hanaya Shop project...

REM Remove temporary files
echo 📁 Removing temporary files...
del /s /q *.tmp 2>nul
del /s /q *.log 2>nul
del /s /q .DS_Store 2>nul
del /s /q Thumbs.db 2>nul

REM Clear Laravel caches
echo 🚀 Clearing Laravel caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

REM Clear application-specific caches
echo 🗂️ Clearing application caches...
php artisan app:clear-cache

REM Optional: Optimize for production (uncomment for production deployment)
REM echo ⚡ Optimizing for production...
REM php artisan config:cache
REM php artisan route:cache
REM php artisan view:cache

echo ✅ Cleanup completed successfully!
echo 📊 Project is now optimized and ready to use.
pause
