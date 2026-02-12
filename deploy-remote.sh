#!/bin/bash

# LogicDir CMS - Server-Side Deployment Script
# Usage: ./deploy-remote.sh [package_name]

PACKAGE=$1
BACKUP_DIR="backups/$(date +%Y%m%d%H%M%S)"
TARGET_DIR="public_html"

if [ -z "$PACKAGE" ]; then
    echo "❌ Error: No package specified."
    exit 1
fi

echo "🚀 Starting remote deployment..."

# 1. Create Backup
echo "💾 Backing up current version..."
mkdir -p ${BACKUP_DIR}
cp -r ${TARGET_DIR} ${BACKUP_DIR}/
# Add DB backup logic here using mysqldump

# 2. Maintenance Mode
echo "🚧 Entering maintenance mode..."
cd ${TARGET_DIR}
php artisan down --secret="deploy-key-2026" || true

# 3. Extract New Version
echo "📂 Extracting package..."
unzip -o ../${PACKAGE} -d .

# 4. Permissions
echo "🔐 Setting permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache

# 5. Database Migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# 6. Post-Deploy Optimization
echo "⚡ Warming up cache..."
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Exit Maintenance Mode
echo "✨ Deployment successful. Exiting maintenance mode..."
php artisan up

echo "✅ Deployment completed!"
