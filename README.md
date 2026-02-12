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

## 📅 Roadmap & Development Phases

### Phase 1: Foundation & Security (RBAC)
- **Modular Core**: Advanced module loader with dependency resolution.
- **RBAC System**: Role-Based Access Control and fine-grained permissions.
- **Web Installer**: Graphical installer for database and migrations.

### Phase 2: Content Management System
- **Polymorphic Architecture**: Unified content table for Pages, Posts, and Custom Types.
- **Hierarchical Data**: Nested sets for categories and adjacency lists for folders.
- **Multi-language**: Built-in translation layer for global content reach.

### Phase 3: Advanced Media Library
- **Chunked Uploads**: Resumable, multi-file uploads with progress tracking.
- **Automatic Optimization**: Real-time conversion to WebP and responsive variants.
- **In-Browser Editor**: Canvas-based editor for cropping, rotation, and filters.

### Phase 4: SEO, Shortcodes & Performance
- **Enterprise SEO**: JSON-LD generation, XML sitemaps, and smart 301 redirects.
- **Shortcode Engine**: WordPress-style shortcode system with AST generation.
- **Performance Suite**: Full-page response caching and .htaccess hardening.

### Phase 5: Ad Management & AdSense
- **Flexible Units**: Support for Google AdSense and custom banner placements.
- **Auto-Injection**: Rules-based engine for injecting ads into content paragraphs.
- **UX Protection**: CLS-prevention placeholders and ad-block detection.

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
4. **Run Web Installer**
   - Navigate to `http://localhost/cms/public/install` in your browser to complete the setup.

### B. WAMP (Windows)
1. **Clone to www**: `C:\wamp64\www\cms`
2. **Setup**: Run `composer install` & `npm install`.
3. **Database**: Use cPanel-like default (Username: `root`, Password: ``).

### C. MAMP (Mac)
1. **Clone to htdocs**: `/Applications/MAMP/htdocs/cms`
2. **Setup**: Use PHP 8.2+ from MAMP settings.
3. **Database**: Port `8889`, Password `root`.

### D. Laragon (Windows)
1. **Clone to www**: `C:\laragon\www\cms`
2. **Access**: Automatically creates `http://cms.test`.

---

## 📦 Modular System (app/Modules)

LogicDir is built on a modular architecture. Each module handles a specific domain:

- **`Installer`**: Handles the `/install` web route for first-time setup.
- **`Core`**: The system kernel, containing base logic and contracts.
- **`User`**: Manages users, RBAC (Roles/Permissions), and authentication.
- **`Media`**: Professional asset manager with chunked uploads and Image Editor.
- **`Content`**: The core CMS engine for Pages, Posts, and Categories.
- **`Seo`**: Meta tag management, XML sitemaps, and OpenGraph logic.
- **`Adsense`**: Monetary integration for banner ads and Google AdSense.
- **`Security`**: Audit logging, rate limiting, and server-side hardening.

### Module Registration
To add or enable a module, register its provider in `bootstrap/providers.php`.

---

## 🌐 Deployment (cPanel/DirectAdmin)

### 1. Structure
- Upload the core folder to `/home/username/cms_core`.
- Move `public/*` content to your `/home/username/public_html/`.

### 2. File Path Fix
In `public_html/index.php`:
```php
require __DIR__.'/../cms_core/vendor/autoload.php';
$app = require_once __DIR__.'/../cms_core/bootstrap/app.php';
```

### 3. Production Optimization
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
```

---

## 🛡 Security & Support
- **Logs**: Found in `storage/logs/laravel.log`.
- **Debugging**: Ensure `APP_DEBUG=false` in production.
- **Permissions**: Folders `755`, Files `644`, Storage `775`.

### 4. Run Web Installer
Navigate to `https://yourdomain.com/install` to finalize the system setup.
