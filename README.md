# LogicDir CMS

LogicDir is a high-performance, modular Content Management System built with **Laravel 12**, **Inertia.js**, and **Vue 3**. It is engineered specifically for premium web applications and websites, with a focus on ease of deployment in shared hosting environments without sacrificing modern developer experience.

## 🚀 Core Features
- **Modular Architecture**: Every feature is a self-contained module in `app/Modules`.
- **Dynamic Theme System**: Production-ready theme engine with `ThemeService` and "Rafa" default theme.
- **Enterprise Performance**: Targeted 95+ PageSpeed scores via aggressive caching and optimization.
- **Shared Hosting Friendly**: Optimized for standard PHP environments (cPanel/DirectAdmin).
- **Developer First**: Fully typed PHP 8.2+ backend with Vue 3 Composition API frontend.

## 🛠 Technology Stack
- **Backend**: Laravel 12, PHP 8.2+, MySQL 8.0+
- **Frontend**: Inertia.js, Vue 3, Tailwind CSS v4, Vite
- **Processing**: Intervention Image v3, Canvas API
- **Infrastructure**: Custom Module System, Hook/Action API

---

## 📋 System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Node.js**: 20+ with npm
- **Web Server**: Apache 2.4+ with `mod_rewrite` enabled

### Required PHP Extensions
- `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `gd`

---

## 🚀 Installation Guide

### 1. Clone & Setup
```bash
git clone <repository-url> cms
cd cms
composer install
npm install
copy .env.example .env
php artisan key:generate
```

### 2. Database Setup
1. Create a database named `cms`.
2. Update `.env` with your database credentials:
   ```env
   DB_DATABASE=cms
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
   *This will install the default "Rafa" theme.*

### 3. Build Assets
```bash
npm run build
```

### 4. Optimize (Production)
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Finalize Installation
Navigate to `http://localhost/cms/public/install` (or your domain) to run the Web Installer and create your admin account.

---

## 🎨 Theme System

LogicDir features a robust, database-driven theme system.

- **Location**: `resources/themes/`
- **Default Theme**: "Rafa" (`resources/themes/rafa/`)
- **Management**: Admin Panel → Appearance → Themes
- **Architecture**:
    - `ThemeService`: Resolves views dynamically (e.g., `themes::rafa.views.home`).
    - `AppearanceServiceProvider`: Registers theme namespaces.
    - `themes` table: Tracks active theme status.

---

## 🌐 Shared Hosting Deployment

1.  **Upload Core**: Upload the entire project to a non-public folder (e.g., `~/cms_core`).
2.  **Public Folder**: Move everything inside `public/` to your `public_html` folder.
3.  **Update Paths**: Edit `public_html/index.php`:
    ```php
    require __DIR__.'/../cms_core/vendor/autoload.php';
    $app = require_once __DIR__.'/../cms_core/bootstrap/app.php';
    ```
4.  **Database**: Import your local database or run migrations via SSH.
5.  **Symlink**: Create a storage link:
    ```bash
    ln -s ~/cms_core/storage/app/public ~/public_html/storage
    ```

---

### 4. Run Web Installer
Navigate to `https://yourdomain.com/install` to finalize the system setup.

---

## 🛡 Security & Maintenance
- **Debug Mode**: always set `APP_DEBUG=false` in production.
- **Permissions**: Folders `755`, Files `644`, Storage `775`.
- **Logs**: Check `storage/logs/laravel.log` for errors.
