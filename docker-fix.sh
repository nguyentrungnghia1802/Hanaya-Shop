#!/bin/bash

echo "=== Docker Issue Fix Script ==="

# 1. Clean up Docker
echo "🧹 Cleaning Docker system..."
sudo docker system prune -af
sudo docker volume prune -f

# 2. Restart Docker
echo "🔄 Restarting Docker..."
sudo systemctl restart docker
sleep 5

# 3. Remove problematic containers/images
echo "🗑️ Removing old containers..."
sudo docker stop $(sudo docker ps -aq) 2>/dev/null || true
sudo docker rm $(sudo docker ps -aq) 2>/dev/null || true

# 4. Clear Docker cache
echo "💾 Clearing Docker cache..."
sudo rm -rf /var/lib/docker/tmp/* 2>/dev/null || true

# 5. Pull images individually
echo "📦 Pulling images individually..."
sudo docker pull redis:7-alpine
sudo docker pull mysql:8.0  
sudo docker pull assassincreed2k1/hanaya-shop:latest

echo "✅ Docker cleanup complete. Now run:"
echo "sudo docker-compose up -d"
