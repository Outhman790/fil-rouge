#!/bin/bash

APP_DIR="/var/www/sandik"
BRANCH="main"
LOG_FILE="/var/log/deploy.log"

echo "👤 Who am I?"
whoami

# Check sudo availability
if sudo -n true 2>/dev/null; then
  echo "🛠️ Can I sudo? Yes"
else
  echo "❌ Cannot sudo. Exiting."
  exit 1
fi

echo "🔧 Starting deployment..." | tee -a $LOG_FILE
cd $APP_DIR || exit 1

# Save current commit hash
PREV_COMMIT=$(git rev-parse HEAD)
echo "$(date '+%F %T') - Previous commit: $PREV_COMMIT" >> $LOG_FILE

# Pull latest code
echo "📥 Pulling latest code..." | tee -a $LOG_FILE
git fetch origin
git reset --hard origin/$BRANCH

# Install PHP dependencies as www-data
echo "📦 Installing Composer deps as www-data..." | tee -a $LOG_FILE
sudo -u www-data composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
COMPOSER_STATUS=$?
if [ $COMPOSER_STATUS -ne 0 ]; then
  echo "❌ Composer failed" | tee -a $LOG_FILE
  exit 1
fi

# Set ownership and permissions
echo "🔒 Fixing permissions..." | tee -a $LOG_FILE
sudo chown -R www-data:www-data $APP_DIR
sudo find $APP_DIR -type f -exec chmod 644 {} \;
sudo find $APP_DIR -type d -exec chmod 755 {} \;

# Health check
echo "💡 Running health check..." | tee -a $LOG_FILE
curl -s --max-time 10 http://localhost | grep -q "<title>"
if [ $? -ne 0 ]; then
  echo "❌ Health check failed! Rolling back..." | tee -a $LOG_FILE
  git reset --hard $PREV_COMMIT
  sudo -u www-data composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
  echo "✅ Rolled back to previous commit: $PREV_COMMIT" | tee -a $LOG_FILE
  exit 1
fi

# Restart services
echo "🔁 Reloading PHP-FPM and Nginx..."
sudo systemctl reload php8.1-fpm
sudo systemctl reload nginx

echo "✅ Deployment successful at $(date '+%F %T')" | tee -a $LOG_FILE
