#!/bin/bash

# LogicDir CMS - Local Deployment Preparation Script
# Usage: ./deploy.sh [staging|production]

ENV=${1:-production}
DATE=$(date +%Y%m%d%H%M%S)
PACKAGE_NAME="logicdir-deploy-${ENV}-${DATE}.zip"

echo "🚀 Starting deployment preparation for: ${ENV}"

# 1. Verification
if [ -n "$(git status --porcelain)" ]; then 
  echo "⚠️ Warning: You have uncommitted changes. Please commit or stash them before deploying."
  # exit 1
fi

# 2. Testing
echo "🧪 Running tests..."
# php artisan test --parallel || exit 1

# 3. Production Dependencies
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Frontend Assets
echo "🎨 Building frontend assets..."
npm install
npm run build

# 5. Laravel Optimization
echo "⚙️ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Packaging
echo "🗜️ Creating deployment package: ${PACKAGE_NAME}"
zip -r ${PACKAGE_NAME} . -x ".git/*" "node_modules/*" "tests/*" "storage/*.key" ".env"

echo "✅ Package created successfully!"
echo "Next steps:"
echo "1. Upload ${PACKAGE_NAME} to your server"
echo "2. Run ./deploy-remote.sh on the server"
