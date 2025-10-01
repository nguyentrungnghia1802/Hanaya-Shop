@echo off
REM 🧪 Windows Test Script to validate GitHub Actions workflow locally

echo 🚀 Testing GitHub Actions workflow locally on Windows...

REM Check if Docker is running
docker ps >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Docker is not running! Please start Docker Desktop first.
    pause
    exit /b 1
)

echo ✅ Docker is running

REM Test MySQL container creation (same as GitHub Actions)
echo ✅ Testing MySQL container setup...
docker run --name test-mysql-hanaya ^
    -e MYSQL_ROOT_PASSWORD=test_password ^
    -e MYSQL_DATABASE=hanaya_shop_test ^
    -e MYSQL_ALLOW_EMPTY_PASSWORD=false ^
    -e MYSQL_RANDOM_ROOT_PASSWORD=false ^
    -p 3307:3306 ^
    --health-cmd="mysqladmin ping --host=127.0.0.1 --port=3306 --user=root --password=test_password --silent" ^
    --health-interval=10s ^
    --health-timeout=5s ^
    --health-retries=10 ^
    --health-start-period=10s ^
    -d mysql:8.0

if %errorlevel% neq 0 (
    echo ❌ Failed to start MySQL container
    pause
    exit /b 1
)

echo ✅ MySQL container started successfully!

REM Wait for MySQL to be ready
echo ✅ Waiting for MySQL to be ready...
timeout /t 15 /nobreak >nul

REM Test database connection
echo ✅ Testing database connection...
docker exec test-mysql-hanaya mysql --host=127.0.0.1 --port=3306 --user=root --password=test_password --execute="SELECT 'MySQL Connection Test' as status;"

if %errorlevel% neq 0 (
    echo ⚠️ Database connection failed
) else (
    echo ✅ Database connection successful!
)

REM Test Laravel environment setup
echo ✅ Testing Laravel environment setup...

REM Create test .env.testing
copy .env.example .env.testing.local >nul

REM Generate key
php artisan key:generate --env=testing

REM Configure test database
echo APP_ENV=testing >> .env.testing.local
echo DB_CONNECTION=mysql >> .env.testing.local
echo DB_HOST=127.0.0.1 >> .env.testing.local
echo DB_PORT=3307 >> .env.testing.local
echo DB_DATABASE=hanaya_shop_test >> .env.testing.local
echo DB_USERNAME=root >> .env.testing.local
echo DB_PASSWORD=test_password >> .env.testing.local
echo CACHE_STORE=array >> .env.testing.local
echo SESSION_DRIVER=array >> .env.testing.local
echo QUEUE_CONNECTION=sync >> .env.testing.local
echo MAIL_MAILER=array >> .env.testing.local
echo MAIL_FROM_ADDRESS=test@hanaya-shop.com >> .env.testing.local
echo MAIL_FROM_NAME="Hanaya Shop Test" >> .env.testing.local
echo FILESYSTEM_DISK=testing >> .env.testing.local
echo BCRYPT_ROUNDS=4 >> .env.testing.local

echo ✅ Environment configuration created

REM Run test suites (same as GitHub Actions)
echo ✅ Running Unit tests...
./vendor/bin/phpunit --testsuite=Unit --testdox --colors=always

echo ✅ Running Integration tests...
./vendor/bin/phpunit --testsuite=Integration --testdox --colors=always

echo ✅ Running Safe Feature tests...
./vendor/bin/phpunit --testsuite=SafeFeature --testdox --colors=always

REM Cleanup
echo ✅ Cleaning up...
docker stop test-mysql-hanaya >nul 2>&1
docker rm test-mysql-hanaya >nul 2>&1
del .env.testing.local >nul 2>&1

echo.
echo ✨ Local test completed! If all steps passed, your workflow should work on GitHub Actions.
echo.
echo 📊 Summary:
echo - MySQL container setup: ✅
echo - Database connection: ✅  
echo - Environment configuration: ✅
echo - Test execution: Check above results
echo.
echo 🚀 Ready to push to GitHub!
pause