# LogicDir CMS

LogicDir is a high-performance, modular Content Management System built with **Laravel 12**, **Inertia.js**, and **Vue 3**. It is engineered specifically for premium web applications and websites, with a focus on ease of deployment in shared hosting environments without sacrificing modern developer experience.

## 🚀 Core Philosophy
- **Modular by Design**: Every feature is a self-contained module with its own lifecycle, routes, and hooks.
- **Enterprise Performance**: Targeted 95+ PageSpeed scores via aggressive caching, WebP optimization, and critical CSS strategies.
- **Shared Hosting Friendly**: Optimized for file-based drivers and standard PHP environments, avoiding heavy dependencies like Elasticsearch or Redis where possible.
- **Developer First**: Fully typed PHP 8.2+ backend with Vue 3 Composition API frontend.

## 🛠 Technology Stack
- **Backend**: Laravel 12, PHP 8.2+, MySQL
- **Frontend**: Inertia.js, Vue 3, Tailwind CSS
- **Processing**: Intervention Image v3 (Image handling), Canvas API (Image editing)
- **Infrastructure**: Custom Module System, Hook/Action API

---

## 📅 Roadmap & Development Phases

### Phase 1: Foundation & Security (RBAC)
The bedrock of the system, focusing on modularity and access control.
- **Modular Core**: Advanced module loader with dependency resolution and lifecycle hooks.
- **RBAC System**: Role-Based Access Control with fine-grained permissions and super-admin overrides.
- **Web Installer**: Multi-step graphical installer for database, migrations, and admin setup.

### Phase 2: Content Management System
A flexible content engine capable of handling various data structures.
- **Polymorphic Architecture**: Unified `contents` table supporting Pages, Posts, and custom types via STI.
- **Hierarchical Data**: Nested sets for categories and adjacency lists for folders.
- **Multi-language**: Translation layer for global content reach.
- **Revision History**: Full audit trail and versioning for all content nodes.

### Phase 3: Advanced Media Library
A professional-grade asset management suite.
- **Chunked Uploads**: Resumable, multi-file uploads for large assets with progress tracking.
- **Automatic Optimization**: Real-time conversion to WebP and generation of 4 responsive variants (Thumbnail, Medium, Large, Hero).
- **In-Browser Editor**: Canvas-based image editor for cropping (Free, 1:1, 16:9, 4:3), rotation, and filters.
- **Folder Management**: Recursive folder structures with drag-and-drop support.

### Phase 4: SEO, Shortcodes & Performance
Optimization and extensibility at an enterprise scale.
- **Enterprise SEO**: Automatic JSON-LD (Schema.org) generation, XML sitemaps, and smart 301 redirects on slug changes.
- **Shortcode Engine**: Recursive, WordPress-style shortcode system with AST generation for SPA hydration.
- **Performance Suite**: Full-page response caching, .htaccess hardening, and lazy-loading composables.

### Phase 5: Ad Management & AdSense Integration
Comprehensive ad placement and monetization system.
- **Flexible Units**: Support for Google AdSense (Auto-ads & Manual), custom banners, and HTML/JS snippets.
- **Auto-Injection**: Rules-based engine to automatically insert ads after specific paragraph counts.
- **UX Protection**: Built-in AdBlocker detection with polite fallbacks and CLS-prevention placeholders.
- **Analytics**: Performance tracking for impressions and clicks with privacy-compliant IP hashing.

### Phase 6: Security Hardening & Hardened Configuration
Enterprise-grade protection suite for high-risk shared hosting environments.
- **Middleware Security**: Nonce-based CSP, HSTS, and global XSS filtering.
- **Audit Logging**: Full trail for admin actions and data mutations with the `LogsActivity` trait.
- **System Monitoring**: Security dashboard for tracking threats and failed login attempts.
- **Integrity Checks**: CLI-based security auditor for configuration and permission validation.

---

## ⚡ Quick Start
1. Clone the repository.
2. Run `composer install` and `npm install`.
3. Configure your initial environment by copying `.env.example` to `.env` (optional, the installer can handle this).
4. Access `/install` in your browser. The **Web Installer** will guide you through database configuration and automatically generate your `.env` file if it doesn't exist.
5. Run `npm run dev` for local development.

---
© 2026 LogicDir CMS. Built for Performance.
