#!/bin/bash

# Deploy Script - Chạy trên server
# Được gọi từ CI/CD pipeline

PROJECT_DIR="/opt/hanaya-shop"
IMAGE_NAME="assassincreed2k1/hanaya-shop:latest"

echo "🚀 Bắt đầu deployment..."

cd $PROJECT_DIR

# 1. Pull latest image
echo "📥 Pull Docker image mới nhất..."
docker pull $IMAGE_NAME

# 2. Stop containers
echo "⏹️ Dừng containers hiện tại..."
docker-compose down

# 3. Start containers
echo "▶️ Khởi động containers..."
docker-compose up -d

# 4. Wait and check health
echo "⏳ Đợi containers khởi động..."
sleep 30

# 5. Health check
echo "🏥 Kiểm tra health..."
if curl -f http://localhost:80 > /dev/null 2>&1; then
    echo "✅ Deployment thành công!"
else
    echo "❌ Deployment thất bại!"
    exit 1
fi