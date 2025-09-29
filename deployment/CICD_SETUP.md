# GitHub Secrets Configuration Guide

## Required Secrets for CI/CD

To enable the CI/CD pipeline, you need to configure the following secrets in your GitHub repository:

### 1. Docker Hub Secrets

Go to **Settings** → **Secrets and variables** → **Actions** → **New repository secret**

**DOCKERHUB_USERNAME**
- Value: `assassincreed2k1`
- Description: Your Docker Hub username

**DOCKERHUB_TOKEN**
- Value: Your Docker Hub access token
- Description: Create at https://hub.docker.com/settings/security
- ⚠️ **Important**: Use Access Token, not password

### 2. Production Server SSH Secrets

**PRODUCTION_HOST**
- Value: Your server IP or domain (e.g., `192.168.1.100` or `server.example.com`)
- Description: Production server hostname/IP

**PRODUCTION_USER**
- Value: `root` (or your SSH username)
- Description: SSH username for server access

**PRODUCTION_SSH_KEY**
- Value: Your private SSH key content
- Description: SSH private key for server authentication
- Format: Copy entire content of your `~/.ssh/id_rsa` file

**PRODUCTION_PORT** (Optional)
- Value: `22` (default SSH port)
- Description: SSH port number

### 3. Optional Notification Secrets

**SLACK_WEBHOOK** (Optional)
- Value: Your Slack webhook URL
- Description: For deployment notifications
- Get from: https://api.slack.com/apps

## Setup Instructions

### Step 1: Generate SSH Key (if not exists)

On your local machine:
```bash
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
```

### Step 2: Copy Public Key to Server

Copy the public key to your production server:
```bash
ssh-copy-id root@your-server-ip
# or manually copy ~/.ssh/id_rsa.pub to server's ~/.ssh/authorized_keys
```

### Step 3: Test SSH Connection

Test the connection:
```bash
ssh root@your-server-ip
```

### Step 4: Add Secrets to GitHub

1. Go to your repository on GitHub
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each secret with the exact name and value

### Step 5: Setup Production Environment

In GitHub, create a production environment:
1. Go to **Settings** → **Environments**
2. Click **New environment**
3. Name: `production`
4. Add protection rules (optional):
   - Required reviewers
   - Wait timer
   - Deployment branches (only main)

## Server Preparation

On your Ubuntu server, ensure:

1. **Docker and Docker Compose are installed**
2. **Project directory exists**: `/opt/hanaya-shop`
3. **Docker Compose file** is present on server
4. **Firewall** allows HTTP/HTTPS traffic
5. **Domain** points to server IP

### Server Setup Commands

```bash
# Create project directory
sudo mkdir -p /opt/hanaya-shop
cd /opt/hanaya-shop

# Create necessary directories (matching server structure)
sudo mkdir -p backups/db logs/app logs/redis public/images scripts

# Set permissions
sudo chown -R $USER:$USER /opt/hanaya-shop

# Create docker-compose.yml (copy your production compose file)
# Make sure it uses the correct image: assassincreed2k1/hanaya-shop:latest

# Copy deployment scripts to server
# Place deploy-production.sh in /opt/hanaya-shop/scripts/
# Make scripts executable
chmod +x /opt/hanaya-shop/scripts/*.sh
```

## Testing the Pipeline

1. **Test CI**: Push to `develop` branch
   - Should run tests and Docker build
   - Should NOT deploy

2. **Test CD**: Push/merge to `main` branch
   - Should run tests, build image, push to Docker Hub
   - Should deploy to production server

## Troubleshooting

### Common Issues:

**SSH Connection Failed**
- Check server IP/hostname
- Verify SSH key is correct
- Ensure port 22 is open
- Test SSH connection manually

**Docker Hub Push Failed**
- Verify Docker Hub username/token
- Check repository name format
- Ensure token has push permissions

**Deployment Failed**
- Check server has enough disk space
- Verify Docker/Docker Compose versions
- Check container logs: `docker compose logs`

### Debug Commands:

```bash
# Check containers
docker compose ps

# View logs
docker compose logs app

# Check disk space
df -h

# Check memory
free -h
```

## Security Notes

- Never commit secrets to code
- Use SSH keys instead of passwords
- Regularly rotate access tokens
- Monitor deployment logs
- Use environment-specific configurations