#!/bin/bash

echo "Deploying to production..."
docker compose pull
docker compose up -d
echo "Production deployment complete."

echo "Application is now running at: http://localhost"

echo "Deployment completed successfully!"

echo "Use the following commands to manage your application:"
echo "docker compose logs -f"
echo "docker compose down"
echo "docker compose restart"

echo "Finished at $(date)"
#!/bin/bash

# Hanaya Shop - Production Deployment Script
# This script should be placed on the production server at /opt/hanaya-shop/scripts/

set -e

# Configuration
PROJECT_DIR="/opt/hanaya-shop"
BACKUP_DIR="/opt/hanaya-shop/backups/db"
LOG_FILE="/opt/hanaya-shop/logs/deploy.log"
SCRIPTS_DIR="/opt/hanaya-shop/scripts"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    error "Please run as root or with sudo"
fi

# Ensure we're in the project directory
cd "$PROJECT_DIR" || error "Cannot access project directory: $PROJECT_DIR"

# Create log file if not exists
mkdir -p "$(dirname "$LOG_FILE")"

log "🚀 Starting Hanaya Shop deployment..."

# 1. Pre-deployment checks
log "🔍 Running pre-deployment checks..."

# Check Docker and Docker Compose
if ! command -v docker &> /dev/null; then
    error "Docker is not installed"
fi

if ! command -v docker compose &> /dev/null; then
    error "Docker Compose is not installed"
fi

# Check if containers are running
if ! docker compose ps | grep -q "Up"; then
    warning "Some containers are not running"
fi

# 2. Create backup (using existing backup script structure)
log "💾 Creating backup..."
BACKUP_DATE=$(date +"%Y-%m-%d_%H-%M-%S")

# Backup database using the same method as update-image.sh
log "Backing up database..."
BACKUP_FILE="$BACKUP_DIR/hanaya_shop_$BACKUP_DATE.sql.gz"
docker compose exec db sh -c "mysqldump -u root -p'Trungnghia2703' --single-transaction hanaya_shop | gzip > /backups/hanaya_shop_$BACKUP_DATE.sql.gz" || warning "Database backup failed"
log "Backup completed: $BACKUP_FILE"

# 3. Pull latest images
log "🔄 Pulling latest Docker images..."
docker compose pull || error "Failed to pull images"

# 4. Update application
log "🔧 Updating application..."

# Stop services gracefully
log "Stopping services..."
docker compose down --remove-orphans

# Start services
log "Starting services..."
docker compose up -d || error "Failed to start services"

# Wait for services to be ready
log "⏳ Waiting for services to be ready..."
sleep 30

# 5. Run Laravel optimizations (matching update-image.sh approach)
log "🏗️ Running Laravel optimizations..."
docker compose exec app php artisan cache:clear || warning "Cache clear failed"
docker compose exec app php artisan config:clear || warning "Config clear failed"
docker compose exec app php artisan route:clear || warning "Route clear failed"
docker compose exec app php artisan view:clear || warning "View clear failed"
docker compose exec app php artisan optimize:clear || warning "Optimize clear failed"
docker compose exec app php artisan optimize || warning "Optimize failed"

# 6. Health checks (matching update-image.sh approach)
log "🏥 Performing health checks..."
sleep 5

# Check web server health endpoint
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health)
if [ "$HTTP_CODE" -eq 200 ]; then
    success "Application is healthy! Response code: $HTTP_CODE"
else
    warning "Application health check failed. Response code: $HTTP_CODE"
    log "Showing recent app logs for debugging:"
    docker compose logs app --tail=30
fi

# Check individual services
if docker compose exec redis redis-cli ping | grep -q "PONG"; then
    success "Redis is healthy"
else
    warning "Redis health check failed"
fi

# Check database
if docker compose exec db mysqladmin ping -h localhost -u root -p'Trungnghia2703' > /dev/null 2>&1; then
    success "Database is healthy"
else
    warning "Database health check failed"
fi

# Check queue worker
if docker compose ps queue | grep -q "Up"; then
    success "Queue worker is running"
else
    warning "Queue worker is not running"
fi

# 7. Clean up
log "🧹 Cleaning up..."
docker image prune -f
docker volume prune -f

# 8. Final status
log "📊 Deployment Status:"
docker compose ps

success "🎉 Deployment completed successfully!"
log "📝 Logs available at: $LOG_FILE"
log "🔗 Application URL: http://www.hanayashop.com"

# Display browser cache clearing instructions (matching update-image.sh)
echo "============================================================"
echo "Để đảm bảo dữ liệu mới nhất, hãy xóa cache trình duyệt:"
echo "- Nhấn Ctrl+Shift+R hoặc Ctrl+F5 trên trình duyệt để reload cứng."
echo "- Hoặc vào Cài đặt > Lịch sử > Xóa dữ liệu duyệt web."
echo "============================================================"

echo "===== DEPLOYMENT COMPLETE ====="
echo "Hanaya Shop has been updated to the latest version."
echo "Finished at $(date)"

# Send notification (optional - requires setup)
# curl -X POST -H 'Content-type: application/json' \
#   --data '{"text":"🚀 Hanaya Shop deployed successfully!"}' \
#   "$SLACK_WEBHOOK_URL" || true