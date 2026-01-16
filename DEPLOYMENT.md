# 🚀 Deployment Guide - Chemistry Maker Platform

## ⚠️ Important: Why Netlify Won't Work

**Netlify does NOT support PHP applications.** Netlify only hosts static sites (HTML/CSS/JS).

Your Chemistry Maker platform requires:
- ✅ PHP 8.0+ (server-side processing)
- ✅ MySQL database (currently using mock)
- ✅ Session management
- ✅ File uploads

You need a **PHP-compatible hosting provider**.

---

## 🎯 Recommended Hosting Options

### Option 1: Shared Hosting (Easiest - Best for Beginners)

**Recommended Providers:**
- **Hostinger** - ₹149/month (~$2/mo) - Great for India
- **Bluehost** - $2.95/month
- **SiteGround** - $3.99/month

**Steps:**
1. Purchase hosting plan with PHP 8.0+ support
2. Get cPanel access from provider
3. Upload files via FTP/File Manager
4. Create MySQL database via cPanel
5. Import `database/chemistry_maker.sql`
6. Update `config/db.php` with real database credentials

**Pros:** Easy setup, cPanel included, email hosting
**Cons:** Shared resources, limited scalability

---

### Option 2: VPS Hosting (More Control)

**Recommended Providers:**
- **DigitalOcean** - $6/month (Droplet)
- **Linode** - $5/month
- **Vultr** - $5/month

**Steps:**
1. Create a VPS (Ubuntu 22.04 recommended)
2. Install LAMP stack:
   ```bash
   sudo apt update
   sudo apt install apache2 php8.1 mysql-server php8.1-mysql
   ```
3. Upload files to `/var/www/html/`
4. Configure Apache virtual host
5. Setup MySQL database
6. Configure SSL with Let's Encrypt (free)

**Pros:** Full control, scalable, better performance
**Cons:** Requires terminal/Linux knowledge

---

### Option 3: Platform-as-a-Service (Easiest Deployment)

#### **Railway.app** (Recommended for Students - Free Tier)
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login and deploy
railway login
railway init
railway up
```

**Setup:**
- Add MySQL plugin from Railway dashboard
- Set environment variables
- Automatic deployments from GitHub

**Free Tier:** 500 hours/month, ₹0 cost

#### **Render.com**
- Free tier available
- Supports PHP via Docker
- Auto-deploys from GitHub

---

## 📋 Pre-Deployment Checklist

### 1. Switch from Mock to Real Database

**Edit `config/db.php`:**
```php
<?php
// Remove MockPDO, use real PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
```

### 2. Create Real Database

**Import SQL file:**
```bash
mysql -u username -p database_name < database/chemistry_maker.sql
```

Or via phpMyAdmin (in cPanel):
1. Create new database
2. Import `database/chemistry_maker.sql`

### 3. Update Configuration

**Edit `config/constants.php`:**
```php
define('SITE_NAME', 'Chemistry Maker');
define('BASE_URL', 'https://yourwebsite.com/'); // Update with your domain
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('RAZORPAY_KEY_ID', 'rzp_live_YOUR_KEY'); // Use live keys
define('RAZORPAY_KEY_SECRET', 'YOUR_LIVE_SECRET');
```

### 4. Security Hardening

**Create `.htaccess` in root:**
```apache
# Prevent access to .git directory
<Files .git>
    Order allow,deny
    Deny from all
</Files>

# Prevent directory listing
Options -Indexes

# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

**Set proper file permissions:**
```bash
# Directories
find . -type d -exec chmod 755 {} \;

# Files
find . -type f -exec chmod 644 {} \;

# Upload directories (writable)
chmod 777 public_html/uploads/
```

### 5. Environment Variables (Optional - Better Security)

Create `.env` file:
```
DB_HOST=localhost
DB_NAME=chemistry_maker
DB_USER=your_user
DB_PASS=your_password
RAZORPAY_KEY_ID=rzp_live_xxx
RAZORPAY_KEY_SECRET=xxx
```

---

## 📤 Deployment Methods

### Method 1: FTP Upload (Shared Hosting)

**Tools:** FileZilla, WinSCP

1. Connect to your hosting FTP
2. Upload all files to `public_html/` or `www/`
3. Ensure `public_html/` folder becomes the root
4. Set permissions for upload directories

### Method 2: Git Deploy (VPS)

```bash
# On server
cd /var/www/html
git clone https://github.com/SelvaUx/Chemistry-Maker-Education-Platform.git .
composer install # if using composer
```

### Method 3: Railway/Render (Auto-deploy)

1. Connect GitHub repository
2. Add buildpacks/Dockerfile
3. Set environment variables
4. Deploy automatically on git push

---

## 🔧 Post-Deployment Steps

### 1. Test Everything
- ✅ Homepage loads
- ✅ Login works (admin + student)
- ✅ Course pages display
- ✅ Video player works
- ✅ Payment flow functional
- ✅ File uploads working

### 2. Setup Razorpay (Production)
- Switch from test keys to live keys
- Configure webhooks for payment verification
- Test payment flow with small amount

### 3. SSL Certificate
**For VPS (Free with Let's Encrypt):**
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

**For Shared Hosting:**
- Usually included free via cPanel
- Enable from SSL/TLS section

### 4. Setup Email (Optional)
- Configure SMTP for student notifications
- Use Gmail SMTP or hosting provider's mail server

---

## 🎓 Tutorial: Deploy to Hostinger (Step-by-Step)

### Step 1: Purchase Hosting
1. Go to Hostinger.com
2. Select "Premium Web Hosting" (₹149/month)
3. Choose domain or use existing
4. Complete payment

### Step 2: Access cPanel
1. Login to Hostinger control panel
2. Click "Manage" on your hosting plan
3. Find cPanel login credentials

### Step 3: Upload Files
**Option A: File Manager**
1. Open File Manager in cPanel
2. Navigate to `public_html/`
3. Upload ZIP of your project
4. Extract ZIP

**Option B: FTP (FileZilla)**
1. Download FileZilla
2. Connect using FTP credentials from Hostinger
3. Upload all files to `public_html/`

### Step 4: Create Database
1. In cPanel, find "MySQL Databases"
2. Create new database: `chemistry_maker`
3. Create user with password
4. Assign user to database (All privileges)
5. Note: hostname, database name, username, password

### Step 5: Import Database
1. Open phpMyAdmin from cPanel
2. Select your database
3. Click "Import" tab
4. Choose `database/chemistry_maker.sql`
5. Click "Go"

### Step 6: Configure Application
1. Edit `config/constants.php` via File Manager
2. Update:
   - `BASE_URL` to your domain
   - Database credentials
3. Edit `config/db.php` to use real PDO (remove MockPDO)

### Step 7: Test
- Visit your domain
- Test login, courses, admin panel
- Fix any errors shown

---

## 🆘 Troubleshooting

### "500 Internal Server Error"
- Check file permissions (755 for folders, 644 for files)
- Enable error display in php.ini or .htaccess
- Check Apache error logs

### "Database connection failed"
- Verify credentials in constants.php
- Check if database exists
- Ensure user has privileges

### File uploads not working
- Check upload directory permissions (777)
- Verify `upload_max_filesize` in php.ini
- Create directories if missing

### Images/CSS not loading
- Check `BASE_URL` in constants.php
- Verify file paths are correct
- Clear browser cache

---

## 📊 Estimated Costs

| Option | Monthly Cost | Best For |
|--------|-------------|----------|
| **Hostinger** | ₹149 (~$2) | Beginners, small sites |
| **DigitalOcean** | $6 | Developers, scalability |
| **Railway** | Free tier | Students, testing |
| **AWS/GCP** | Variable | Enterprise, high traffic |

---

## 🎯 Recommended: Start with Hostinger

For your use case (education platform in India):
1. **Hostinger Premium** - ₹149/month
2. Includes: cPanel, MySQL, SSL, Email
3. Easy setup, good support
4. Upgrade to VPS later if needed

**Next Steps:**
1. Purchase Hostinger hosting
2. Follow tutorial above
3. Deploy and test
4. Configure Razorpay live keys
5. Go live! 🚀

---

## 📞 Need Help?

Common deployment issues and solutions are documented above. For hosting-specific issues:
- **Hostinger:** 24/7 live chat support
- **DigitalOcean:** Community forums
- **Railway:** Discord community

Good luck with deployment! 🎉
