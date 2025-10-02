#!/bin/bash

echo "=== Hanaya Shop Deployment Verification ==="
echo "Starting comprehensive system check..."

docker compose ps

echo "Health check..."
curl -f http://localhost/health || echo "Health check failed!"

echo "Deployment verification completed!"
#!/bin/bash

# Comprehensive server deployment verification script
# Usage: ./verify-deployment.sh

# Exit on any error
set -e

echo "=== Hanaya Shop Deployment Verification ==="
echo "Starting comprehensive system check..."
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print status
print_status() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✅ $2${NC}"
    else
        echo -e "${RED}❌ $2${NC}"
    fi
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# 1. Check Docker containers status
echo -e "${BLUE}=== 1. Docker Containers Status ===${NC}"
docker compose ps
echo ""

# Check if all containers are running
containers_status=$(docker compose ps --format "table {{.Service}}\t{{.Status}}" | grep -c "Up" || echo "0")
total_containers=$(docker compose ps --services | wc -l)

if [ $containers_status -eq $total_containers ]; then
    print_status 0 "All containers are running ($containers_status/$total_containers)"
else
    print_status 1 "Some containers are not running ($containers_status/$total_containers)"
fi
echo ""

# 2. Check container logs for errors
echo -e "${BLUE}=== 2. Container Logs Check ===${NC}"
print_info "Checking recent logs for errors..."

# Check app container logs
echo "App container logs (last 10 lines):"
docker compose logs --tail=10 app 2>/dev/null || echo "No app logs found"
echo ""

# Check queue worker logs
echo "Queue worker logs (last 10 lines):"
docker compose logs --tail=10 queue 2>/dev/null || echo "No queue logs found"
echo ""

# 3. Health Check
echo -e "${BLUE}=== 3. Application Health Check ===${NC}"
health_response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health || echo "000")
print_status $([ "$health_response" = "200" ] && echo 0 || echo 1) "Health endpoint: HTTP $health_response"

# 4. Database Connection
echo -e "${BLUE}=== 4. Database Connection ===${NC}"
db_check=$(docker compose exec -T app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully';" 2>/dev/null || echo "failed")
print_status $([ "$db_check" != "failed" ] && echo 0 || echo 1) "Database connection"

# 5. Redis Connection
echo -e "${BLUE}=== 5. Redis Connection ===${NC}"
redis_check=$(docker compose exec -T app php artisan tinker --execute="Redis::ping(); echo 'Redis connected successfully';" 2>/dev/null || echo "failed")
print_status $([ "$redis_check" != "failed" ] && echo 0 || echo 1) "Redis connection"

# 6. Queue Worker Status
echo -e "${BLUE}=== 6. Queue Worker Status ===${NC}"
queue_status=$(docker compose exec -T queue php artisan queue:work --once --stop-when-empty 2>&1 | grep -E "(Processing|Processed|No jobs)" | head -1 || echo "failed")
print_status $([ "$queue_status" != "failed" ] && echo 0 || echo 1) "Queue worker functionality"
print_info "Queue status: $queue_status"

# 7. File Permissions
echo -e "${BLUE}=== 7. File Permissions Check ===${NC}"
storage_writable=$(docker compose exec -T app test -w /var/www/html/storage && echo "writable" || echo "not writable")
print_status $([ "$storage_writable" = "writable" ] && echo 0 || echo 1) "Storage directory permissions"

bootstrap_cache_writable=$(docker compose exec -T app test -w /var/www/html/bootstrap/cache && echo "writable" || echo "not writable")
print_status $([ "$bootstrap_cache_writable" = "writable" ] && echo 0 || echo 1) "Bootstrap cache permissions"

# 8. Volume Mounts
echo -e "${BLUE}=== 8. Volume Mounts Verification ===${NC}"
images_mounted=$(docker compose exec -T app ls -la /var/www/html/public/images 2>/dev/null | grep -q "." && echo "mounted" || echo "not mounted")
print_status $([ "$images_mounted" = "mounted" ] && echo 0 || echo 1) "Images directory volume mount"

# 9. Application Configuration
echo -e "${BLUE}=== 9. Application Configuration ===${NC}"
app_key=$(docker compose exec -T app php artisan tinker --execute="echo config('app.key') ? 'set' : 'not set';" 2>/dev/null || echo "failed")
print_status $([ "$app_key" = "set" ] && echo 0 || echo 1) "Application key"

# 10. Test Email/Notification System
echo -e "${BLUE}=== 10. Notification System Test ===${NC}"
print_info "Testing notification queue processing..."
notification_test=$(docker compose exec -T app php artisan tinker --execute="
\$user = App\Models\User::first();
if (\$user) {
    \$order = new stdClass();
    \$order->id = 'TEST-'.time();
    \$user->notify(new App\Notifications\NewOrderPending(\$order));
    echo 'Notification queued successfully';
} else {
    echo 'No users found for testing';
}
" 2>/dev/null || echo "failed")
print_status $([ "$notification_test" != "failed" ] && echo 0 || echo 1) "Notification system"
print_info "Notification test: $notification_test"

# 11. Web Server Response
echo -e "${BLUE}=== 11. Web Server Response ===${NC}"
web_response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/ || echo "000")
print_status $([ "$web_response" = "200" ] && echo 0 || echo 1) "Main page: HTTP $web_response"

# 12. Image Upload Test
echo -e "${BLUE}=== 12. Image Upload Directory Test ===${NC}"
upload_test=$(docker compose exec -T app sh -c "touch /var/www/html/public/images/test.tmp 2>/dev/null && rm -f /var/www/html/public/images/test.tmp 2>/dev/null && echo 'writable'" || echo "not writable")
print_status $([ "$upload_test" = "writable" ] && echo 0 || echo 1) "Image upload directory"

# Summary
echo ""
echo -e "${BLUE}=== Deployment Verification Summary ===${NC}"
print_info "Verification completed at $(date)"
print_warning "If any checks failed, review the logs and configuration"
print_info "Monitor logs with: docker compose logs -f"
print_info "Access application at: http://localhost"

echo ""
echo -e "${GREEN}=== Additional Monitoring Commands ===${NC}"
echo "• Check container resources: docker compose top"
echo "• View detailed logs: docker compose logs -f [service]"  
echo "• Monitor queue jobs: docker compose exec app php artisan queue:monitor"
echo "• Check database tables: docker compose exec app php artisan migrate:status"
echo "• Clear application cache: docker compose exec app php artisan cache:clear"
