# Panduan Instalasi Hotel Booking System

Dokumentasi lengkap untuk menginstal aplikasi Hotel Booking System di server production.

## 📋 Persyaratan Sistem

### Server Requirements
- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **Node.js**: >= 16.x dan NPM >= 8.x
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **Extension PHP yang Diperlukan**:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Tokenizer
  - XML
  - GD atau Imagick (untuk image processing)

## 🚀 Langkah Instalasi

### 1. Clone atau Upload Project

```bash
# Jika menggunakan Git
git clone <repository-url> hotel
cd hotel

# Atau upload file project ke server via FTP/SFTP
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies
npm install

# Build assets untuk production
npm run build
```

### 3. Konfigurasi Environment

```bash
# Copy file .env.example ke .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi File .env

Edit file `.env` dan sesuaikan dengan konfigurasi server Anda:

```env
APP_NAME="Hotel Booking System"
APP_ENV=production
APP_KEY=base64:... (sudah di-generate)
APP_DEBUG=false
APP_URL=http://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Mail Configuration (sesuaikan dengan SMTP Anda)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Timezone
APP_TIMEZONE=Asia/Jakarta
```

### 5. Setup Database

```bash
# Buat database di MySQL/MariaDB
mysql -u root -p
CREATE DATABASE hotel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hotel_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON hotel_db.* TO 'hotel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Atau menggunakan PostgreSQL
createdb hotel_db
```

### 6. Jalankan Migration dan Seeder

```bash
# Jalankan migration
php artisan migrate --force

# Jalankan seeder untuk data awal (optional)
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=SmtpSeeder
```

### 7. Setup Storage Link

```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

### 8. Setup Folder Permissions

```bash
# Set permission untuk storage dan cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Set permission untuk upload folder
chmod -R 775 public/upload
chown -R www-data:www-data public/upload

# Jika menggunakan SELinux (CentOS/RHEL)
chcon -R -t httpd_sys_rw_content_t storage bootstrap/cache
chcon -R -t httpd_sys_rw_content_t public/upload
```

### 9. Optimasi Aplikasi

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 10. Konfigurasi Web Server

#### Apache Configuration

Buat file virtual host di `/etc/apache2/sites-available/hotel.conf`:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/hotel/public

    <Directory /var/www/hotel/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/hotel_error.log
    CustomLog ${APACHE_LOG_DIR}/hotel_access.log combined
</VirtualHost>
```

Aktifkan virtual host:
```bash
sudo a2ensite hotel.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx Configuration

Buat file di `/etc/nginx/sites-available/hotel`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/hotel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan dan restart:
```bash
sudo ln -s /etc/nginx/sites-available/hotel /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 11. Setup SSL (HTTPS) - Recommended

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx  # Untuk Nginx
# atau
sudo apt install certbot python3-certbot-apache  # Untuk Apache

# Generate SSL Certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
# atau
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Auto-renewal sudah di-setup otomatis
```

### 12. Setup Cron Job

Tambahkan cron job untuk Laravel scheduler:

```bash
# Edit crontab
crontab -e

# Tambahkan baris berikut (sesuaikan path)
* * * * * cd /var/www/hotel && php artisan schedule:run >> /dev/null 2>&1
```

### 13. Setup Queue Worker (Optional)

Jika menggunakan queue, setup supervisor:

```bash
# Install supervisor
sudo apt install supervisor

# Buat file config di /etc/supervisor/conf.d/hotel-worker.conf
[program:hotel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hotel/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/hotel/storage/logs/worker.log
stopwaitsecs=3600

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start hotel-worker:*
```

## 🔧 Konfigurasi Tambahan

### Setup SMTP Settings

Setelah instalasi, login ke admin panel dan konfigurasi SMTP settings:
- Masuk ke: `/admin/login`
- Navigasi ke: **Setting > SMTP Setting**
- Isi konfigurasi SMTP sesuai provider email Anda

### Setup Site Settings

Konfigurasi informasi hotel:
- Navigasi ke: **Setting > Site Setting**
- Upload logo hotel
- Isi informasi kontak, alamat, dan social media

### Create Admin User

Jika belum ada admin user, buat melalui seeder atau tinker:

```bash
# Via Tinker
php artisan tinker

# Di dalam tinker:
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@hotel.com',
    'password' => bcrypt('your_password'),
]);
$user->assignRole('admin');
```

## 📁 Struktur Folder yang Perlu Permission

Pastikan folder berikut memiliki permission yang benar:

```bash
storage/          # 775, www-data:www-data
bootstrap/cache/  # 775, www-data:www-data
public/upload/    # 775, www-data:www-data
```

## 🔒 Security Checklist

- [ ] Set `APP_DEBUG=false` di production
- [ ] Set `APP_ENV=production`
- [ ] Gunakan HTTPS (SSL Certificate)
- [ ] Pastikan `.env` file tidak accessible dari web
- [ ] Set permission file yang tepat
- [ ] Disable directory listing di web server
- [ ] Setup firewall (UFW/iptables)
- [ ] Regular backup database
- [ ] Update dependencies secara berkala

## 🗄️ Backup Database

```bash
# Backup MySQL
mysqldump -u hotel_user -p hotel_db > backup_$(date +%Y%m%d).sql

# Restore MySQL
mysql -u hotel_user -p hotel_db < backup_20231201.sql

# Backup PostgreSQL
pg_dump -U hotel_user hotel_db > backup_$(date +%Y%m%d).sql

# Restore PostgreSQL
psql -U hotel_user hotel_db < backup_20231201.sql
```

## 🔄 Update Aplikasi

```bash
# Pull update dari repository
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migration jika ada
php artisan migrate --force

# Clear dan rebuild cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🐛 Troubleshooting

### Error: Permission Denied
```bash
# Fix permission
sudo chown -R www-data:www-data storage bootstrap/cache public/upload
sudo chmod -R 775 storage bootstrap/cache public/upload
```

### Error: Class Not Found
```bash
# Rebuild autoloader
composer dump-autoload --optimize
```

### Error: 500 Internal Server Error
```bash
# Check Laravel log
tail -f storage/logs/laravel.log

# Check web server log
tail -f /var/log/apache2/error.log  # Apache
tail -f /var/log/nginx/error.log    # Nginx
```

### Error: Storage Link Not Working
```bash
# Remove existing link
rm public/storage

# Recreate link
php artisan storage:link
```

## 📞 Support

Jika mengalami masalah saat instalasi, periksa:
1. Laravel log: `storage/logs/laravel.log`
2. Web server error log
3. PHP error log
4. Pastikan semua extension PHP terinstall
5. Pastikan database connection berfungsi

## 📝 Catatan Penting

1. **Jangan commit file `.env`** ke repository
2. **Backup database secara berkala**
3. **Update dependencies** untuk security patches
4. **Monitor log files** untuk error
5. **Setup monitoring** untuk uptime dan performance

## ✅ Verifikasi Instalasi

Setelah instalasi selesai, verifikasi:

1. ✅ Akses homepage: `http://your-domain.com`
2. ✅ Akses admin panel: `http://your-domain.com/admin/login`
3. ✅ Test upload image (gallery, room, dll)
4. ✅ Test booking flow
5. ✅ Test email notification (jika SMTP sudah dikonfigurasi)
6. ✅ Check database connection
7. ✅ Check file permissions

---

**Selamat! Aplikasi Hotel Booking System sudah terinstall di server Anda.** 🎉

