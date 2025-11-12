# 🚀 Wistara Batik E-Commerce - Production Deployment Guide

## 📋 Pre-Deployment Checklist

### ✅ Environment Setup
- [ ] Copy `.env.example` to `.env`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Configure database settings in `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure mail settings
- [ ] Set proper `APP_URL`

### ✅ Database Setup
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeders: `php artisan db:seed`
- [ ] Verify database connections

### ✅ File Permissions
- [ ] Set proper permissions for `storage/` and `bootstrap/cache/`
- [ ] Ensure web server can write to these directories

### ✅ Frontend Assets
- [ ] Install dependencies: `npm install`
- [ ] Build assets: `npm run build`
- [ ] Verify compiled assets in `public/build/`

### ✅ Security Configurations
- [ ] Replace `.htaccess` with `.htaccess.production` for production
- [ ] Configure SSL certificate
- [ ] Set up proper firewall rules
- [ ] Enable HSTS headers (after SSL setup)

### ✅ Performance Optimizations
- [ ] Enable opcode caching (OPcache)
- [ ] Configure database connection pooling
- [ ] Set up CDN for static assets (optional)
- [ ] Enable compression

### ✅ Monitoring & Logging
- [ ] Configure log rotation
- [ ] Set up error monitoring
- [ ] Configure backup schedules

## 🛠️ Deployment Commands

### Quick Setup (Development)
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build
```

### Production Setup
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Environment setup
cp .env.example .env
# Edit .env with production values
php artisan key:generate

# Database
php artisan migrate --force
php artisan db:seed

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 🔧 Production Configuration

### Environment Variables (.env)
```env
APP_NAME="Batik Wistara"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wistaralaravel
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="${APP_NAME}"

# Optional: Configure Redis for caching
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Web Server Configuration (Apache/Nginx)

#### Apache (.htaccess)
- Use the provided `.htaccess.production` file
- Ensure `mod_rewrite` is enabled
- Configure virtual host properly

#### Nginx Example
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/wistaralaravel/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1M;
        add_header Cache-Control "public, immutable";
    }
}
```

## 🔒 Security Best Practices

### File Permissions
```bash
# Set proper permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Secure sensitive files
chmod 600 .env
chmod 600 storage/logs/
```

### SSL Configuration
- Obtain SSL certificate (Let's Encrypt recommended)
- Redirect HTTP to HTTPS
- Enable HSTS headers

### Database Security
- Use strong passwords
- Limit database user privileges
- Enable database backups
- Use prepared statements (Laravel handles this)

## 📊 Performance Monitoring

### Laravel Commands
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Queue Processing (if using queues)
```bash
# Start queue worker
php artisan queue:work --tries=3 --timeout=90

# Or use supervisor for production
```

## 🚨 Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
- Check Laravel logs: `storage/logs/laravel.log`
- Verify file permissions
- Check `.env` configuration
- Ensure vendor dependencies are installed

#### 2. Database Connection Issues
- Verify database credentials in `.env`
- Check database server status
- Ensure database user has proper permissions

#### 3. Assets Not Loading
- Run `npm run build`
- Check file permissions on `public/build/`
- Verify Vite configuration

#### 4. CSRF Token Issues
- Ensure `csrf_token()` is available in views
- Check session configuration
- Verify cookie settings

### Debug Mode (Temporary)
For debugging production issues, temporarily set:
```env
APP_DEBUG=true
APP_ENV=local
```
**Remember to disable this after debugging!**

## 📞 Support

For deployment issues:
1. Check Laravel documentation
2. Review server error logs
3. Verify all checklist items
4. Test in staging environment first

## 🎯 Post-Deployment Checklist

- [ ] Website loads correctly
- [ ] User registration/login works
- [ ] Product browsing functions
- [ ] Shopping cart operations
- [ ] Checkout process completes
- [ ] Admin panel accessible
- [ ] Email notifications work
- [ ] SSL certificate active
- [ ] Performance is acceptable
- [ ] Backups are scheduled

---

**🎉 Your Wistara Batik e-commerce platform is now ready for production!**
