#!/bin/bash

# Clean up deployment files from project
# Run this on Windows development machine

echo "=== Cleaning up deployment files ==="

PROJECT_DIR="c:\xampp\htdocs\Hanaya-Shop"

# Files to remove
FILES_TO_REMOVE=(
    "deploy-ubuntu.sh"
    "update-ubuntu.sh"
    "fix-and-deploy.sh"
    "docker-fix.sh"
    "import-sql.sh"
    "docker-compose.production.yml"
    "DEPLOYMENT_GUIDE.md"
    "QUICK_COMMANDS.md"
    ".env.production"
)

echo "Files to be removed:"
for file in "${FILES_TO_REMOVE[@]}"; do
    if [ -f "$PROJECT_DIR/$file" ]; then
        echo "  - $file ✓"
    else
        echo "  - $file (not found)"
    fi
done

echo ""
read -p "Continue with cleanup? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Cleanup cancelled"
    exit 1
fi

# Remove files
for file in "${FILES_TO_REMOVE[@]}"; do
    if [ -f "$PROJECT_DIR/$file" ]; then
        rm "$PROJECT_DIR/$file"
        echo "✅ Removed: $file"
    fi
done

echo ""
echo "🎉 Cleanup completed!"
echo ""
echo "📋 Remaining deployment files (keep these):"
echo "  - Dockerfile (for building images)"
echo "  - .dockerignore (for Docker builds)"
echo "  - deployment/ folder (server configs)"
echo ""
