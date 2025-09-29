#!/bin/bash

# Hanaya Shop - Production Deployment Script
# This script should be placed on the production server

set -e

# Configuration
PROJECT_DIR="/opt/hanaya-shop"
BACKUP_DIR="/opt/hanaya-shop/backups"
LOG_FILE="/opt/hanaya-shop/logs/deploy.log"

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

# Create necessary directories (using existing structure)
mkdir -p "$BACKUP_DIR/db" "$BACKUP_DIR/containers" "/opt/hanaya-shop/logs"

cd "$PROJECT_DIR" || error "Cannot access project directory: $PROJECT_DIR"

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

# 2. Create backup
log "💾 Creating backup..."
BACKUP_DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
log "Backing up database..."
docker compose exec -T db mysqldump -u root -pTrungnghia2703 --all-databases > "$BACKUP_DIR/db/backup_$BACKUP_DATE.sql" || warning "Database backup failed"

# Backup container states
docker compose ps --format "table {{.Name}}\t{{.Status}}\t{{.Ports}}" > "$BACKUP_DIR/containers/containers_$BACKUP_DATE.log"

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

# 5. Run Laravel optimizations
log "🏗️ Running Laravel optimizations..."
docker compose exec -T app php artisan config:cache || warning "Config cache failed"
docker compose exec -T app php artisan route:cache || warning "Route cache failed"
docker compose exec -T app php artisan view:cache || warning "View cache failed"

# 6. Health checks
log "🏥 Performing health checks..."

# Check web server
if curl -f -s http://localhost/health > /dev/null; then
    success "Web server is healthy"
else
    error "Web server health check failed"
fi

# Check database connection
if docker compose exec -T app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';" | grep -q "Database OK"; then
    success "Database connection is healthy"
else
    warning "Database connection check failed"
fi

# Check Redis connection
if docker compose exec -T redis redis-cli ping | grep -q "PONG"; then
    success "Redis is healthy"
else
    warning "Redis health check failed"
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

# Send notification (optional - requires setup)
# curl -X POST -H 'Content-type: application/json' \
#   --data '{"text":"🚀 Hanaya Shop deployed successfully!"}' \
#   "$SLACK_WEBHOOK_URL" || true