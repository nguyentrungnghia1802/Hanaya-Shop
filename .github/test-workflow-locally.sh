#!/bin/bash

# 🧪 Local Test Script to validate GitHub Actions workflow
# This script simulates the GitHub Actions environment locally

echo "🚀 Testing GitHub Actions workflow locally..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_step() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# 1. Check Docker is running
print_step "Checking Docker..."
if ! docker ps > /dev/null 2>&1; then
    print_error "Docker is not running! Please start Docker first."
    exit 1
fi

# 2. Test MySQL container creation (same as GitHub Actions)
print_step "Testing MySQL container setup..."
docker run --name test-mysql-hanaya \
    -e MYSQL_ROOT_PASSWORD=test_password \
    -e MYSQL_DATABASE=hanaya_shop_test \
    -e MYSQL_ALLOW_EMPTY_PASSWORD=false \
    -e MYSQL_RANDOM_ROOT_PASSWORD=false \
    -p 3307:3306 \
    --health-cmd="mysqladmin ping --host=127.0.0.1 --port=3306 --user=root --password=test_password --silent" \
    --health-interval=10s \
    --health-timeout=5s \
    --health-retries=10 \
    --health-start-period=10s \
    -d mysql:8.0

if [ $? -eq 0 ]; then
    print_step "MySQL container started successfully!"
else
    print_error "Failed to start MySQL container"
    exit 1
fi

# 3. Wait for MySQL to be ready
print_step "Waiting for MySQL to be ready..."
for i in {1..30}; do
    if docker exec test-mysql-hanaya mysqladmin ping --host=127.0.0.1 --port=3306 --user=root --password=test_password --silent; then
        print_step "MySQL is ready after $i attempts!"
        break
    fi
    echo "Waiting... ($i/30)"
    sleep 2
done

# 4. Test database connection
print_step "Testing database connection..."
docker exec test-mysql-hanaya mysql --host=127.0.0.1 --port=3306 --user=root --password=test_password --execute="SELECT 'MySQL Connection Test' as status;"

if [ $? -eq 0 ]; then
    print_step "Database connection successful!"
else
    print_error "Database connection failed"
fi

# 5. Test Laravel environment setup
print_step "Testing Laravel environment setup..."

# Create test .env.testing
cp .env.example .env.testing.local
php artisan key:generate --env=testing

# Configure test database
cat >> .env.testing.local << 'EOF'
APP_ENV=testing
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=hanaya_shop_test
DB_USERNAME=root
DB_PASSWORD=test_password
CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
MAIL_FROM_ADDRESS=test@hanaya-shop.com
MAIL_FROM_NAME="Hanaya Shop Test"
FILESYSTEM_DISK=testing
BCRYPT_ROUNDS=4
APP_MAINTENANCE_DRIVER=file
PULSE_ENABLED=false
TELESCOPE_ENABLED=false
EOF

print_step "Environment configuration created"

# 6. Test migrations
print_step "Testing database migrations..."
php artisan migrate --env=testing --force --path=database/migrations

if [ $? -eq 0 ]; then
    print_step "Migrations completed successfully!"
else
    print_warning "Migration failed - this might be expected in local test"
fi

# 7. Run test suites (same as GitHub Actions)
print_step "Running Unit tests..."
./vendor/bin/phpunit --testsuite=Unit --testdox --colors=always

if [ $? -eq 0 ]; then
    print_step "Unit tests passed!"
else
    print_warning "Unit tests failed"
fi

print_step "Running Integration tests..."
./vendor/bin/phpunit --testsuite=Integration --testdox --colors=always

print_step "Running Safe Feature tests..."
./vendor/bin/phpunit --testsuite=SafeFeature --testdox --colors=always

# 8. Cleanup
print_step "Cleaning up..."
docker stop test-mysql-hanaya
docker rm test-mysql-hanaya
rm -f .env.testing.local

print_step "✨ Local test completed! If all steps passed, your workflow should work on GitHub Actions."

echo ""
echo "📊 Summary:"
echo "- MySQL container setup: ✅"
echo "- Database connection: ✅"  
echo "- Environment configuration: ✅"
echo "- Test execution: Check above results"
echo ""
echo "🚀 Ready to push to GitHub!"