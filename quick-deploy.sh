#!/bin/bash

# Hanaya Shop - Quick Deploy Script
# This script runs from project root and handles all deployment

set -e

echo "🌸 Hanaya Shop - Quick Deploy"
echo "=============================="

# Navigate to deployment directory
cd deployment

# Check if .env exists
if [ ! -f "../.env" ]; then
    echo "⚠️  .env file not found. Creating from template..."
    cp ../.env.production ../.env
    echo "📝 Please edit .env file with your configuration"
    echo "💡 Don't forget to set APP_KEY, DB_PASSWORD, etc."
    exit 1
fi

# Check if APP_KEY is set
if ! grep -q "APP_KEY=base64:" ../.env; then
    echo "🔑 Generating APP_KEY..."
    cd ..
    docker run --rm -v $(pwd):/app composer:2 bash -c "cd /app && php artisan key:generate"
    cd deployment
fi

echo "🚀 Starting deployment..."
./scripts/deploy.sh production $1

echo ""
echo "✅ Deployment completed!"
echo "🌐 Your application is running at: http://localhost"
echo ""
echo "📋 Useful commands:"
echo "  - View logs: cd deployment && docker-compose -f docker-compose.prod.yml logs -f"
echo "  - Stop: cd deployment && docker-compose -f docker-compose.prod.yml down"
echo "  - Shell: cd deployment && docker-compose -f docker-compose.prod.yml exec app bash"
