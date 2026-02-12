# LogicDir CMS

LogicDir is a high-performance, modular Content Management System built with **Laravel 12**, **Inertia.js**, and **Vue 3**. It is engineered specifically for premium web applications and websites, with a focus on ease of deployment in shared hosting environments without sacrificing modern developer experience.

## 🚀 Core Philosophy
- **Modular by Design**: Every feature is a self-contained module with its own lifecycle, routes, and hooks located in `app/Modules`.
- **Enterprise Performance**: Targeted 95+ PageSpeed scores via aggressive caching, WebP optimization, and critical CSS strategies.
- **Shared Hosting Friendly**: Optimized for file-based drivers and standard PHP environments, avoiding root-access requirements.
- **Developer First**: Fully typed PHP 8.2+ backend with Vue 3 Composition API frontend.

## 🛠 Technology Stack
- **Backend**: Laravel 12, PHP 8.2+, MySQL 8.0+
- **Frontend**: Inertia.js, Vue 3, Tailwind CSS, Vite
- **Processing**: Intervention Image v3, Canvas API
- **Infrastructure**: Custom Module System, Hook/Action API

---

##  System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Node.js**: 20+ with npm
- **Composer**: 2.2+
- **Web Server**: Apache 2.4+ with `mod_rewrite` enabled

### Required PHP Extensions
- `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `gd`

---

## 🚀 Local Installation

### A. XAMPP (Windows/Mac/Linux)
1. **Clone to htdocs**
   ```bash
   cd C:\xampp\htdocs
   git clone <repository-url> cms
   cd cms
   ```
2. **Setup**
   ```bash
   composer install
   npm install
   copy .env.example .env
   php artisan key:generate
   ```
3. **Database**
   - Create a database named `cms` in phpMyAdmin (`http://localhost/phpmyadmin`).
   - Run `php artisan migrate --seed`.

---

### B. WAMP (Windows)
1. **Clone to www**
   ```bash
   cd C:\wamp64\www
   git clone <repository-url> cms
   ```
2. **Setup**
   - Open WAMP Terminal or PowerShell in `/cms`.
   - Run `composer install` and `npm install`.
   - Copy `.env.example` to `.env` and run `php artisan key:generate`.
3. **Database**
   - Create `cms` database in phpMyAdmin.
   - Update `DB_PASSWORD` in `.env` if you set one (WAMP default is empty).

---

### C. MAMP (Mac)
1. **Clone to htdocs**
   ```bash
   cd /Applications/MAMP/htdocs
   git clone <repository-url> cms
   ```
2. **Setup**
   - Use MAMP's PHP version (ensure 8.2+).
   - Run `composer install` and `npm install`.
3. **Database**
   - Update `.env`:
     ```env
     DB_PORT=8889
     DB_USERNAME=root
     DB_PASSWORD=root
     ```

---

### D. Laragon (Windows)
1. **Clone to www**
   ```bash
   cd C:\laragon\www
   git clone <repository-url> cms
   ```
2. **Auto-Virtual Host**
   - Laragon will automatically detect the folder and create `http://cms.test`.
3. **Setup**
   - Right-click Laragon -> Terminal.
   - Run `composer install`, `npm install`, and migrate.

---

## 📦 Modular System

The CMS uses a custom module system. Each module is located in `app/Modules`.

### Registering Modules
New modules must be registered in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    
    // Custom Modules
    App\Modules\Seo\Providers\SeoServiceProvider::class,
    App\Modules\User\Providers\UserServiceProvider::class,
    // Add your module provider here
];
```

---

## 🌐 Shared Hosting Deployment

### 1. File Upload
Upload the entire project to a directory **above** `public_html` (e.g., `/home/username/cms_core`).

### 2. Public Directory
Move everything inside the `public/` folder to your `public_html/`.

### 3. Path Configuration
Edit `public_html/index.php` to point to the core directory:
```php
require __DIR__.'/../cms_core/vendor/autoload.php';
$app = require_once __DIR__.'/../cms_core/bootstrap/app.php';
```

### 4. Production Optimization
Run these commands to ensure maximum speed:
```bash
composer install --no-dev --optimize-autoloader
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## � Security Hardening
- **Disable Debug**: Set `APP_DEBUG=false` in `.env`.
- **Protect Environment**: Ensure `.env` is not accessible via web.
- **Permissions**: Set `storage` and `bootstrap/cache` to `775`.

---

## 📚 Technical Support
- **Error Logs**: Located in `storage/logs/laravel.log`.
- **Artisan**: Use `php artisan` for CLI tasks.

© 2026 LogicDir CMS. Built for Performance.
