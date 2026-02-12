#!/bin/bash

# LogicDir CMS - Post-Deployment Optimization Script

echo "⚡ Optimizing system for production..."

# 1. Laravel Optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 2. Image Optimization (Glide/Intervention Cache)
# php artisan media:clear-glide-cache

# 3. Security Check
php artisan logicdir:security-audit

# 4. Cache Warming
echo "🔥 Warming up frontend cache..."
php artisan logicdir:cache-warm

echo "✅ Optimization completed."
