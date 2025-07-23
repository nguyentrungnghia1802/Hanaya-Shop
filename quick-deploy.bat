@echo off
REM Hanaya Shop - Quick Deploy Script for Windows
REM This script runs from project root and handles all deployment

echo 🌸 Hanaya Shop - Quick Deploy
echo ==============================

REM Navigate to deployment directory
cd deployment

REM Check if .env exists
if not exist "..\\.env" (
    echo ⚠️  .env file not found. Creating from template...
    copy "..\.env.production" "..\.env"
    echo 📝 Please edit .env file with your configuration
    echo 💡 Don't forget to set APP_KEY, DB_PASSWORD, etc.
    pause
    exit /b 1
)

REM Check if APP_KEY is set
findstr /C:"APP_KEY=base64:" "..\.env" > nul
if %ERRORLEVEL% NEQ 0 (
    echo 🔑 Generating APP_KEY...
    cd ..
    docker run --rm -v %cd%:/app composer:2 bash -c "cd /app && php artisan key:generate"
    cd deployment
)

echo 🚀 Starting deployment...
call scripts\deploy.bat production %1

echo.
echo ✅ Deployment completed!
echo 🌐 Your application is running at: http://localhost
echo.
echo 📋 Useful commands:
echo   - View logs: cd deployment ^&^& docker-compose -f docker-compose.prod.yml logs -f
echo   - Stop: cd deployment ^&^& docker-compose -f docker-compose.prod.yml down
echo   - Shell: cd deployment ^&^& docker-compose -f docker-compose.prod.yml exec app bash

pause
