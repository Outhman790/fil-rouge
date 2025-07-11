#!/bin/bash

APP_DIR="/var/www/sandik"
BRANCH="main"
LOG_FILE="/var/log/deploy.log"

echo "🔧 Starting deployment..." | tee -a $LOG_FILE
cd $APP_DIR || exit 1

# Save current commit hash
PREV_COMMIT=$(git rev-parse HEAD)
echo "$(date '+%F %T') - Previous commit: $PREV_COMMIT" >> $LOG_FILE

# Fetch and reset to latest
echo "📥 Pulling latest code..." | tee -a $LOG_FILE
git fetch origin
git reset --hard origin/$BRANCH

# Install PHP dependencies
echo "📦 Installing Composer deps..." | tee -a $LOG_FILE
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Set permissions
echo "🔒 Fixing permissions..." | tee -a $LOG_FILE
chown -R www-data:www-data $APP_DIR
find $APP_DIR -type f -exec chmod 644 {} \;
find $APP_DIR -type d -exec chmod 755 {} \;

# Basic healthcheck
echo "💡 Running health check..." | tee -a $LOG_FILE
if ! curl -s --max-time 10 http://localhost | grep -q "<title>"; then
  echo "❌ Health check failed! Rolling back..." | tee -a $LOG_FILE
  git reset --hard $PREV_COMMIT
  composer install --no-dev --prefer-dist --no-interaction
  echo "✅ Rolled back to previous commit: $PREV_COMMIT" | tee -a $LOG_FILE
