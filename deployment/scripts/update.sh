#!/bin/bash

# Update Script - Cập nhật image trên server
# Tương tự update-image.sh nhưng đơn giản hơn

PROJECT_DIR="/opt/hanaya-shop"
IMAGE_NAME="assassincreed2k1/hanaya-shop:latest"

echo "🔄 Cập nhật image..."

cd $PROJECT_DIR

# Pull và restart
docker pull $IMAGE_NAME
docker-compose up -d --force-recreate

echo "✅ Update hoàn thành!"