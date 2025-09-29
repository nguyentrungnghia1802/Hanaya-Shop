# 🚀 DEPLOYMENT STRATEGY & SAFETY GUIDE

## 📋 **WORKFLOW OVERVIEW**

### 1. **Staging Pipeline** (`staging-deploy.yml`)
- **Triggers**: Push to `develop`, `staging` branches hoặc PR vào `main`
- **Purpose**: Test builds, run tests, security scans
- **DockerHub Tags**: `staging-latest`, `staging-{commit-sha}`
- **No Production Impact**: Chỉ build và test, không deploy production

### 2. **Production Pipeline** (`production-deploy.yml`) - **CẢI TIẾN**
- **Triggers**: Push to `main` branch **NHƯNG** ignore các files an toàn:
  ```yaml
  paths-ignore:
    - 'README.md'
    - 'docs/**'
    - '*.md'
    - '.gitignore'
    - 'LICENSE'
    - '#GUIDE/**'
    - 'deployment/README.md'
  ```
- **Manual Override**: `workflow_dispatch` để trigger thủ công
- **Safety**: Chỉ deploy khi có thay đổi code thực sự

## 🔒 **SAFETY MEASURES**

### ✅ **AN TOÀN - KHÔNG TRIGGER DEPLOYMENT**
- Cập nhật README.md
- Sửa documentation trong `docs/`
- Thay đổi .gitignore
- Cập nhật LICENSE
- Sửa guides trong `#GUIDE/`
- Chỉnh sửa deployment docs

### ⚠️ **SẼ TRIGGER DEPLOYMENT**
- Thay đổi PHP code trong `app/`
- Cập nhật dependencies (`composer.json`, `package.json`)
- Sửa config files
- Thay đổi routes, views, controllers
- Cập nhật Dockerfile
- Sửa database migrations

## 🎯 **DEPLOYMENT WORKFLOW**

### **Recommended Git Flow:**
```bash
# 1. Develop & test locally
git checkout develop
# ... make changes ...
git push origin develop  # → Triggers staging build

# 2. Create PR for review
git checkout -b feature/new-feature
git push origin feature/new-feature  # → Triggers staging build on PR

# 3. Merge to main (after review)
git checkout main
git merge feature/new-feature
git push origin main  # → Triggers production deployment (if code changes)
```

## 🛡️ **EMERGENCY PROCEDURES**

### **Rollback Command** (if needed):
```bash
# SSH to server and run:
cd /opt/hanaya-shop/scripts
./rollback.sh  # If you have this script

# Or manually:
docker pull assassincreed2k1/hanaya-shop:previous-working-tag
docker-compose down
docker-compose up -d
```

### **Manual Production Deploy**:
- Go to GitHub Actions
- Click "Production Deploy" workflow
- Click "Run workflow" button
- Select branch and run manually

## 📊 **MONITORING**

### **After Each Deployment**:
1. Check application health: `curl http://www.hanayashop.com`
2. Monitor logs: `docker logs hanaya_app`
3. Database connectivity: `docker exec hanaya_app php artisan migrate:status`
4. Queue status: `docker logs hanaya_queue`

### **Key Metrics to Watch**:
- Response time
- Database connections
- Redis cache status
- Application errors in logs

## 💡 **BEST PRACTICES**

1. **Always test locally first**
2. **Use staging environment for testing**
3. **Review changes carefully before merging to main**
4. **Monitor deployment results**
5. **Keep database backups current**
6. **Test rollback procedures periodically**

## 🔧 **DOCKER BUILD PROCESS EXPLAINED**

### **Where builds happen:**
- **GitHub Actions runners** (Ubuntu VMs in GitHub cloud)
- **NOT on your local machine**
- **NOT on production server**

### **What's included in build:**
```
Project Root/
├── Dockerfile          ✅ Used for build instructions
├── composer.json       ✅ PHP dependencies
├── package.json        ✅ Node.js dependencies  
├── app/               ✅ All your PHP code
├── resources/         ✅ Views, assets
├── public/            ✅ Web files
├── routes/            ✅ Route definitions
├── config/            ✅ Configuration
└── database/          ✅ Migrations, seeders
```

### **Build flow:**
```
GitHub → Runner → Docker Build → DockerHub → Production Server Pull
```

## ⚡ **PERFORMANCE OPTIMIZATIONS**

1. **Docker Layer Caching**: Enabled with `cache-from: type=gha`
2. **Multi-stage builds**: If using complex Dockerfile
3. **Dependency caching**: Composer and npm caches
4. **Image size optimization**: Remove dev dependencies in production

---

**🎉 Result: Much safer deployment with minimal production risks!**