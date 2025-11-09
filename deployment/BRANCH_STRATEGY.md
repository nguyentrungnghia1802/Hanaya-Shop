# Branch Strategy & Workflow Guide

## Branch Structure

### Main Branches

**`main`** (Production)
- Always stable and deployable
- Reflects production state
- Only merge from `develop` after thorough testing
- Triggers automatic deployment to production

**`develop`** (Development)  
- Integration branch for features
- Should always be in a releasable state
- All feature branches merge here first
- Runs comprehensive tests

### Supporting Branches

**`feature/*`** (Feature Development)
- Branch from: `develop`
- Merge back to: `develop`
- Naming: `feature/user-authentication`, `feature/payment-integration`
- Deleted after merge

**`hotfix/*`** (Production Fixes)
- Branch from: `main`
- Merge to: `main` AND `develop`
- Naming: `hotfix/critical-security-fix`
- For urgent production fixes only

**`release/*`** (Optional - for release preparation)
- Branch from: `develop`
- Merge to: `main` and `develop`
- Naming: `release/v1.2.0`

## Workflow Process

### 1. Feature Development
```bash
# Start new feature
git checkout develop
git pull origin develop
git checkout -b feature/new-feature-name

# Work on feature
git add .
git commit -m "Add new feature implementation"
git push origin feature/new-feature-name

# Create PR to develop
# After review and CI passes, merge to develop
```

### 2. Release to Production
```bash
# From develop to main
git checkout main
git pull origin main
git merge develop
git push origin main

# This triggers automatic deployment
```

### 3. Hotfix Process
```bash
# Emergency fix
git checkout main
git pull origin main
git checkout -b hotfix/critical-fix

# Fix and test
git add .
git commit -m "Fix critical issue"
git push origin hotfix/critical-fix

# Merge to main (triggers deployment)
git checkout main
git merge hotfix/critical-fix
git push origin main

# Also merge to develop
git checkout develop
git merge hotfix/critical-fix
git push origin develop
```

## CI/CD Triggers

### Continuous Integration (CI)
**Triggers on:**
- Push to `develop`
- Push to `feature/*` branches
- Pull requests to `develop` or `main`

**Actions:**
- Run PHP tests (PHPUnit)
- Run code linting (PHP Pint)
- Build Docker image (test only)
- Security vulnerability scan
- Frontend build test

### Continuous Deployment (CD)
**Triggers on:**
- Push to `main` branch only
- Manual workflow dispatch

**Actions:**
- Run full CI pipeline
- Build production Docker image
- Push image to Docker Hub
- Deploy to production server
- Run health checks
- Send notifications

## Branch Protection Rules

### For `main` branch:
- Require pull request reviews
- Require status checks to pass
- Require branches to be up to date
- Restrict pushes to pull requests
- Do not allow force pushes

### For `develop` branch:
- Require status checks to pass
- Require branches to be up to date
- Allow force pushes (for maintainers)

## Commands Reference

### Delete old branches (recommended)
```bash
# Delete deploy and test branches (not needed with CI/CD)
git branch -d deploy test
git push origin --delete deploy test

# Clean up merged feature branches
git branch --merged develop | grep feature/ | xargs -n 1 git branch -d
```

### Sync with remote
```bash
# Update all branches
git fetch --all --prune

# Switch to develop
git checkout develop
git pull origin develop
```

### Create feature branch
```bash
git checkout develop
git pull origin develop
git checkout -b feature/your-feature-name
```

## Best Practices

### Commit Messages
```
feat: add user authentication system
fix: resolve payment gateway timeout issue
docs: update API documentation
style: format code according to PSR-12
refactor: optimize database queries
test: add unit tests for user model
```

### Before Merging
- [ ] All tests pass
- [ ] Code review completed
- [ ] Documentation updated
- [ ] No merge conflicts
- [ ] Feature tested locally

### Deployment Checklist
- [ ] Tests pass on develop
- [ ] Database migrations reviewed
- [ ] Environment variables updated
- [ ] Backup created
- [ ] Rollback plan ready

## Troubleshooting

### Failed CI/CD Pipeline
1. Check GitHub Actions logs
2. Verify secrets are configured
3. Test Docker build locally
4. Check server connectivity

### Merge Conflicts
1. Pull latest changes from target branch
2. Resolve conflicts locally
3. Test thoroughly
4. Push resolved changes

### Rollback Deployment
```bash
# On production server
cd /opt/hanaya-shop
docker compose down
docker compose pull assassincreed2k1/hanaya-shop:previous-tag
docker compose up -d
```

## Branch Cleanup Commands

```bash
# Delete remote branches that don't exist anymore
git remote prune origin

# List branches that can be deleted
git branch --merged develop | grep -v develop | grep -v main

# Delete them
git branch --merged develop | grep -v develop | grep -v main | xargs -n 1 git branch -d
```