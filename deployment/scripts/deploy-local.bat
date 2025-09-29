@echo off
REM Hanaya Shop - Windows Deployment Script for Local Development

setlocal enabledelayedexpansion

echo [INFO] Starting Hanaya Shop deployment on Windows...

REM Check Docker
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker is not installed or not running
    exit /b 1
)

REM Check Docker Compose
docker compose version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker Compose is not available
    exit /b 1
)

REM Navigate to project directory
cd /d "%~dp0\..\..\"

echo [INFO] Pulling latest images...
docker compose -f docker-compose.yml pull

echo [INFO] Stopping existing containers...
docker compose down --remove-orphans

echo [INFO] Starting services...
docker compose up -d

echo [INFO] Waiting for services to start...
timeout /t 30 /nobreak

echo [INFO] Checking container status...
docker compose ps

echo [INFO] Performing health check...
curl -f http://localhost/health || echo [WARNING] Health check failed

echo [SUCCESS] Deployment completed!
echo [INFO] Application available at: http://localhost

pause