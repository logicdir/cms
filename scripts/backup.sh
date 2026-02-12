#!/bin/bash

# LogicDir CMS - Backup Script
# Usage: ./scripts/backup.sh

DATE=$(date +%Y%m%d%H%M%S)
BACKUP_PATH="storage/backups/${DATE}"
mkdir -p ${BACKUP_PATH}

echo "💾 Starting full backup: ${DATE}"

# 1. Database Backup
# Note: On shared hosting, we use credentials from .env
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2)

echo "🗄️ Backing up database..."
mysqldump -h ${DB_HOST} -u ${DB_USER} -p${DB_PASS} ${DB_NAME} > ${BACKUP_PATH}/db_backup.sql

# 2. Files Backup (Public/Uploads)
echo "📂 Backing up public uploads..."
tar -czf ${BACKUP_PATH}/uploads.tar.gz public/storage

echo "✅ Backup completed at: ${BACKUP_PATH}"
