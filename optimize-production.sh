#!/bin/bash

# 🚀 Wistara Batik Production Optimization Script
# Run this script before deploying to production

echo "🚀 Starting Wistara Batik Production Optimization..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Laravel project root directory"
    exit 1
fi

echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --quiet

echo "📦 Installing Node.js dependencies..."
npm install --silent

echo "🔨 Building frontend assets..."
npm run build --silent

echo "🗄️ Running database migrations..."
php artisan migrate --force --quiet

echo "🌱 Running database seeders..."
php artisan db:seed --quiet

echo "⚡ Optimizing Laravel for production..."
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
php artisan optimize --quiet

echo "🧹 Clearing old caches..."
php artisan cache:clear --quiet
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet

echo "📁 Setting proper file permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

echo "🔒 Securing sensitive files..."
chmod 600 .env 2>/dev/null || echo "⚠️  .env file not found or already secured"

echo "📊 Generating application key (if not set)..."
php artisan key:generate --force --quiet

echo "✅ Production optimization completed!"
echo ""
echo "🎯 Next steps:"
echo "1. Copy .env.example to .env and configure production settings"
echo "2. Set up your web server (Apache/Nginx)"
echo "3. Replace .htaccess with .htaccess.production"
echo "4. Configure SSL certificate"
echo "5. Set up database backups"
echo "6. Test the application thoroughly"
echo ""
echo "📖 See DEPLOYMENT_README.md for detailed instructions"
echo ""
echo "🎉 Your Wistara Batik e-commerce platform is ready for production!"
