#!/bin/bash

# Server Setup Script for Hanaya Shop
# Thiết lập server Ubuntu cho deployment

echo "🚀 Bắt đầu setup server Ubuntu..."

# 1. Update system
sudo apt update && sudo apt upgrade -y

# 2. Install Docker
if ! command -v docker &> /dev/null; then
    echo "📦 Cài đặt Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker $USER
    rm get-docker.sh
fi

# 3. Install Docker Compose
if ! command -v docker-compose &> /dev/null; then
    echo "📦 Cài đặt Docker Compose..."
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
fi

# 4. Create project directory
sudo mkdir -p /opt/hanaya-shop
sudo chown $USER:$USER /opt/hanaya-shop

echo "✅ Server setup hoàn thành!"
echo "📍 Project directory: /opt/hanaya-shop"