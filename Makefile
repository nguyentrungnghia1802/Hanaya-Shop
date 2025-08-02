# Hanaya Shop Makefile
# Commands for building and deploying the application

DOCKER_IMAGE = assassincreed2k1/hanaya-shop:latest
COMPOSE_FILE = docker-compose.production.yml

.PHONY: help build push deploy update stop logs clean

help:	## Show this help message
	@echo "Hanaya Shop - Available commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

build:	## Build Docker image
	@echo "Building Docker image..."
	docker build -t $(DOCKER_IMAGE) .
	@echo "✅ Build completed!"

push:	## Push Docker image to Docker Hub
	@echo "Pushing Docker image to Docker Hub..."
	docker push $(DOCKER_IMAGE)
	@echo "✅ Push completed!"

build-push: build push	## Build and push Docker image

deploy:	## Deploy application (first time)
	@echo "Deploying Hanaya Shop..."
	chmod +x deploy-ubuntu.sh
	./deploy-ubuntu.sh

update:	## Update application with latest image
	@echo "Updating Hanaya Shop..."
	chmod +x update-ubuntu.sh
	./update-ubuntu.sh

stop:	## Stop all services
	@echo "Stopping all services..."
	docker-compose -f $(COMPOSE_FILE) down

logs:	## Show application logs
	docker-compose -f $(COMPOSE_FILE) logs -f app

logs-all:	## Show all services logs
	docker-compose -f $(COMPOSE_FILE) logs -f

status:	## Show services status
	docker-compose -f $(COMPOSE_FILE) ps

restart:	## Restart all services
	docker-compose -f $(COMPOSE_FILE) restart

clean:	## Clean up containers and images
	@echo "Cleaning up..."
	docker-compose -f $(COMPOSE_FILE) down --remove-orphans
	docker system prune -f
	@echo "✅ Cleanup completed!"

# Development commands
dev-build:	## Build for development
	docker build -t hanaya-shop:dev .

dev-run:	## Run development version
	docker run -p 8000:80 hanaya-shop:dev
