# Hotel Booking System

Sistem manajemen booking hotel berbasis Laravel dengan fitur lengkap untuk mengelola kamar, booking, gallery, blog, dan kontak.

## 🚀 Quick Start

### Instalasi di Server Production

**📖 [Lihat Panduan Instalasi Lengkap](INSTALLATION.md)**

### Instalasi Cepat (Development)

```bash
# Clone repository
git clone <repository-url> hotel
cd hotel

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=hotel_db
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migration
php artisan migrate

# Run seeder (optional)
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

## 📋 Fitur Utama

### Frontend
- ✅ Homepage dengan slider dan informasi hotel
- ✅ Pencarian kamar berdasarkan tanggal
- ✅ Detail kamar dengan multi-image
- ✅ Booking system dengan checkout
- ✅ Payment integration (Stripe)
- ✅ Gallery
- ✅ Blog dengan kategori
- ✅ About Us page
- ✅ Restaurant page
- ✅ Contact form
- ✅ User dashboard untuk melihat booking
- ✅ Multi-language support (EN/ID)

### Backend (Admin Panel)
- ✅ Dashboard dengan statistik
- ✅ Manajemen Room Type & Room
- ✅ Manajemen Room Numbers
- ✅ Booking management dengan invoice
- ✅ Assign room ke booking
- ✅ Booking report dengan filter tanggal
- ✅ Gallery management (multi-image upload)
- ✅ Blog management (Category & Post)
- ✅ Comment management
- ✅ Team management
- ✅ Testimonial management
- ✅ Contact message management
- ✅ Site settings (Logo, Contact Info, Social Media)
- ✅ SMTP settings
- ✅ Role & Permission management
- ✅ Admin user management

## 🛠️ Teknologi yang Digunakan

- **Framework**: Laravel 10.x
- **PHP**: 8.1+
- **Database**: MySQL/MariaDB/PostgreSQL
- **Frontend**: Blade Templates, jQuery, Bootstrap
- **Editor**: CKEditor 5
- **PDF**: DomPDF
- **Payment**: Stripe
- **Permission**: Spatie Laravel Permission
- **Image Processing**: Intervention Image

## 📦 Dependencies

### PHP Packages
- `barryvdh/laravel-dompdf` - PDF generation
- `spatie/laravel-permission` - Role & Permission
- `stripe/stripe-php` - Payment gateway
- `intervention/image` - Image manipulation
- `maatwebsite/excel` - Excel import/export

### NPM Packages
- `vite` - Build tool
- `tailwindcss` - CSS framework
- `alpinejs` - JavaScript framework

## 📁 Struktur Project

```
hotel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Backend/        # Admin controllers
│   │   └── Frontend/       # Frontend controllers
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   └── views/
│       ├── admin/          # Admin layout
│       ├── backend/        # Admin views
│       └── frontend/       # Frontend views
├── public/
│   ├── upload/             # Uploaded files
│   └── storage/            # Storage symlink
└── routes/
    └── web.php             # Web routes
```

## 🔐 Default Login

Setelah menjalankan seeder, gunakan kredensial berikut:

**Admin:**
- Email: `admin@admin.com`
- Password: `password`

*⚠️ Pastikan untuk mengubah password setelah instalasi pertama!*

## 📚 Dokumentasi

- [📖 Panduan Instalasi Server](INSTALLATION.md)
- [🔐 Panduan Permission & Role](PERMISSION_GUIDE.md)
- [🌐 Panduan Multi-Language](LANGUAGE_SETUP.md)

## 🔧 Konfigurasi Penting

### Environment Variables

Pastikan konfigurasi berikut di file `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=hotel_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### Folder Permissions

```bash
chmod -R 775 storage bootstrap/cache public/upload
chown -R www-data:www-data storage bootstrap/cache public/upload
```

## 🚀 Deployment

Lihat [INSTALLATION.md](INSTALLATION.md) untuk panduan lengkap deployment di server production.

## 📝 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Support

Untuk bantuan dan dukungan, silakan buka issue di repository atau hubungi developer.

---

**Dibuat dengan ❤️ menggunakan Laravel Framework**
