# Production Deployment & Maintenance Guide

This document provides a comprehensive guide for deploying and maintaining the **SSV-New Distributor CRM** in a production environment.

## 1. Essential Environment Setup (`.env`)

Ensure your `.env` file reflects production-ready values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_production_user
DB_PASSWORD=your_secure_password

# For Security
LOG_CHANNEL=daily
LOG_LEVEL=error

# Performance
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

---

## 2. Infrastructure Scheduling (The Cron Job)

To enable features like **Database Pruning**, **Automatic Reporting**, and **Scheduled Tasks**, you must configure the Laravel Scheduler on your server.

The command to run is:
`* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

### A. AWS (EC2 / Ubuntu / Linux)
1. SSH into your instance.
2. Run `crontab -e`.
3. Add the following line at the end:
   `* * * * * cd /var/www/html/ssv-new && php artisan schedule:run >> /dev/null 2>&1`

### B. Plesk
1. Go to **Scheduled Tasks** in the left sidebar.
2. Click **Add Task**.
3. Select **Run a PHP script**.
4. **Script path**: `app/artisan`
5. **Arguments**: `schedule:run`
6. **Execution time**: Choose "Cron style" and enter `* * * * *`.
7. **PHP version**: Ensure it matches your site's version (PHP 8.2+).

### C. cPanel
1. Search for **Cron Jobs** in the cPanel search bar.
2. Under "Add New Cron Job", select "Once Per Minute" (`* * * * *`).
3. Enter the command (ensure you use the full path to PHP):
   `/usr/local/bin/php /home/username/public_html/ssv-new/artisan schedule:run >> /dev/null 2>&1`

---

## 3. Production Deployment Commands

Always run these commands during a deployment to ensure the application is optimized:

```bash
# 1. Update dependencies securely
composer install --optimize-autoloader --no-dev

# 2. Build assets
npm install && npm run build

# 3. Synchronize database (Safe for live data)
php artisan migrate --force

# 4. Clear and Cache Configuration/Routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Link storage for file uploads
php artisan storage:link
```

---

## 4. Maintenance & Security Checklist

### Folder Permissions
Ensure the following folders are writable by the web server (usually `www-data` or your user):
*   `storage/`
*   `bootstrap/cache/`
*   `public/uploads/`

### Activity Log Pruning
The application is pre-configured to automatically prune logs older than one year. This task runs daily at midnight (as scheduled in `bootstrap/app.php`). No manual intervention is needed as long as the Cron job is active.

### Security Updates
Run `composer audit` regularly on your local machine before pushing updates to check for known vulnerabilities in your dependencies.
