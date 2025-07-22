@echo off
REM Deployment script for Hanaya Shop on Windows
REM Usage: deploy.bat [environment]

set ENVIRONMENT=%1
if "%ENVIRONMENT%"=="" set ENVIRONMENT=production
set PROJECT_NAME=hanaya-shop
set BACKUP_DIR=.\backups

echo 🌸 Deploying Hanaya Shop to %ENVIRONMENT% environment...

REM Create backup directory
if not exist %BACKUP_DIR% mkdir %BACKUP_DIR%

REM Backup database before deployment
echo 📦 Creating database backup...
docker ps | findstr "%PROJECT_NAME%-db" > nul
if %ERRORLEVEL% EQU 0 (
    for /f "tokens=2 delims= " %%i in ('date /t') do set date=%%i
    for /f "tokens=1 delims= " %%i in ('time /t') do set time=%%i
    set timestamp=%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%
    docker exec %PROJECT_NAME%-db mysqldump -u root -p%DB_ROOT_PASSWORD% %DB_DATABASE% > %BACKUP_DIR%\backup_%timestamp%.sql
    echo ✅ Database backup created
)

REM Pull latest changes from git
echo 📡 Pulling latest changes...
git pull origin main

REM Build and start containers
echo 🔨 Building and starting containers...
docker-compose -f deployment/docker-compose.prod.yml down
docker-compose -f deployment/docker-compose.prod.yml build --no-cache
docker-compose -f deployment/docker-compose.prod.yml up -d

REM Wait for services to be ready
echo ⏳ Waiting for services to start...
timeout /t 30 /nobreak > nul

REM Run database migrations
echo 🗄️ Running database migrations...
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan migrate --force

REM Seed database if needed (first deployment)
if "%2"=="--seed" (
    echo 🌱 Seeding database...
    docker-compose -f deployment/docker-compose.prod.yml exec app php artisan db:seed --force
)

REM Clear and cache everything
echo 🧹 Clearing and caching...
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan optimize

REM Create storage link
echo 🔗 Creating storage link...
docker-compose -f deployment/docker-compose.prod.yml exec app php artisan storage:link

REM Set proper permissions
echo 🔐 Setting permissions...
docker-compose -f deployment/docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html
docker-compose -f deployment/docker-compose.prod.yml exec app chmod -R 755 /var/www/html
docker-compose -f deployment/docker-compose.prod.yml exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

REM Health check
echo 🏥 Running health check...
timeout /t 10 /nobreak > nul
curl -f http://localhost/ > nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✅ Application is healthy and running!
    echo 🌐 Visit http://localhost to see your application
) else (
    echo ❌ Health check failed!
    echo 📋 Checking logs...
    docker-compose -f deployment/docker-compose.prod.yml logs app
    exit /b 1
)

REM Cleanup old images
echo 🧹 Cleaning up old Docker images...
docker image prune -f

echo 🎉 Deployment completed successfully!
echo.
echo 📊 Container Status:
docker-compose -f deployment/docker-compose.prod.yml ps

echo.
echo 📈 Quick Stats:
echo - Application: http://localhost
echo - Database: MySQL 8.0 (Port 3306)
echo - Redis: Port 6379
echo - Logs: docker-compose -f deployment/docker-compose.prod.yml logs -f

echo.
echo 🛠️ Useful Commands:
echo - View logs: docker-compose -f deployment/docker-compose.prod.yml logs -f app
echo - Access shell: docker-compose -f deployment/docker-compose.prod.yml exec app bash
echo - Stop: docker-compose -f deployment/docker-compose.prod.yml down
echo - Restart: docker-compose -f deployment/docker-compose.prod.yml restart

pause
