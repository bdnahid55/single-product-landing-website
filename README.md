# 🍯 খাঁটি সুন্দরবনের মধু (Khanti Sundarban Modhu)
### Premium E-Commerce Landing Page & Full-Featured Admin Back-Office

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Test Suite](https://img.shields.io/badge/Tests-56%20Passed%20(400%20Assertions)-success?style=for-the-badge&logo=checkmarx&logoColor=white)](tests)
[![Payment Gateway](https://img.shields.io/badge/SSLCommerz-Official%20PHP%20Integrated-008080?style=for-the-badge&logo=visa&logoColor=white)](https://sslcommerz.com)
[![Deployment](https://img.shields.io/badge/Shared%20Hosting-cPanel%20Ready-F1A80A?style=for-the-badge&logo=cpanel&logoColor=white)](https://cpanel.net)

A production-ready, ultra high-converting single and multi-product e-commerce landing page built specifically for honey, organic foods, and direct-to-consumer (D2C) businesses in Bangladesh. Powered by **Laravel 13**, **Bootstrap 5**, and **AdminLTE 3**, featuring **Cash on Delivery (COD)** + **SSLCommerz (bKash, Nagad, Rocket, Cards)** payment gateways, live parcel tracking, multi-layer bot & spam rate-limiting, Spatie Role-Based Access Control (RBAC), Admin 2FA (OTP), on-demand media & database backups, and shared hosting (cPanel) zero-symlink file uploads.

---

## 📑 Table of Contents
1. [Key Features](#-key-features)
2. [Tech Stack & System Requirements](#-tech-stack--system-requirements)
3. [Quick Start (Local Development)](#-quick-start-local-development)
4. [Default Credentials](#-default-credentials)
5. [cPanel / Shared Hosting Deployment Guide](#-cpanel--shared-hosting-deployment-guide)
6. [VPS / Ubuntu / Nginx Deployment Guide](#-vps--ubuntu--nginx-deployment-guide)
7. [Admin Panel & Feature Walkthrough](#-admin-panel--feature-walkthrough)
8. [Automated Testing](#-automated-testing)
9. [Project Architecture & File Layout](#-project-architecture--file-layout)
10. [Security & Production Hardening](#-security--production-hardening)
11. [License & Support](#-license--support)

---

## 🌟 Key Features

### 🛍️ Customer Frontend (High-Converting Landing Page)
- **Single & Multi-Product Mode (Funnel Switcher):** Easily switch between a laser-focused Single Product Landing Funnel or an interactive Multi-Product Honey Package Picker from the Admin Panel.
- **Cash on Delivery (COD) & Online Payment (SSLCommerz):** Seamlessly supports Cash on Delivery as well as instant digital payments via bKash, Nagad, Rocket, Upay, Visa, MasterCard, and DBBL Nexus through SSLCommerz.
- **Official SSLCommerz PHP Integration:** Built following the official [`sslcommerz/SSLCommerz-PHP`](https://github.com/sslcommerz/SSLCommerz-PHP) standard library with complete server-side order validation (`orderValidate`), IPN webhook, and CSRF token handling.
- **Dynamic Delivery Charge Calculation:** Instant toggle between "Inside Dhaka" (৳80 default) and "Outside Dhaka" (৳130 default) with live JavaScript total calculation.
- **Public Order Tracking (`/track-order`):** Customers can track their real-time parcel fulfillment and delivery status using their Order ID (e.g. `ORD-261121-16D285`) and Phone Number.
- **Stock & Availability Safeguards:** Prevents checkout of out-of-stock or unpublished products; notifies customers if an item is sold out.
- **Order Success Page:** Clean order confirmation memo with print capability and instant link to tracking.
- **SEO & Social Sharing Ready:** Open Graph, Twitter Cards, Google Search Console meta tag, dynamic JSON-LD Product & Organization Schema, canonical URLs, and dynamic sitemap (`/sitemap.xml`).
- **Speed & Cache Busting:** Automatically appends cache-busting version hashes to CSS/JS so design updates reflect immediately across customer browsers.

### 🛡️ Bot & Spam Protection
- **Anti-Bot Invisible Honeypot:** Traps automated submission scripts before they hit the database.
- **Order Rate Limiter (`throttle:orders`):** Strict 5 orders/minute limit per IP with friendly Bengali warning messages to prevent checkout flooding.
- **Order Tracking Rate Limiter (`throttle:order-tracking`):** 15 lookups/minute limit to prevent database query scraping.
- **Double-Click Submit Protection:** Disables submit buttons and shows loading spinners upon submission to prevent duplicate orders.

### 🔐 Admin Back-Office (AdminLTE 3)
- **Analytics Dashboard:** Total orders, revenue, pending vs delivered charts, and customer overview.
- **Order Management & Invoicing:**
  - View full order history, filter by fulfillment status (`Pending`, `Processing`, `Delivered`, `Cancelled`).
  - Print professional, branded customer A4 invoices with one click.
  - Granular **Order Status Management Permission** (`update-order-status`): Restricts fulfillment and payment status changes strictly to authorized roles (read-only view for unauthorized staff).
  - Courier / Steadfast Consignment ID notes tracking.
- **Role-Based Access Control (Spatie RBAC):**
  - Create and manage custom administrative roles (`Manager`, `Editor`, `Order Support`, etc.).
  - 9 granular permissions: `view-dashboard`, `manage-orders`, `update-order-status`, `manage-products`, `manage-reviews`, `manage-sections`, `manage-settings`, `manage-admins`, `manage-roles`.
  - Built-in safeguards preventing deletion or privilege loss of the primary Super Admin.
- **Two-Factor Authentication (Email OTP):**
  - 6-digit secure login verification code emailed on login with a 10-minute expiry window.
  - Rate-limited resend button (60s cooldown) and maximum 5 failed attempts lockout.
  - Development fallback: OTP is automatically logged to `storage/logs/laravel.log` when testing locally without an active mail server.
- **Products Catalog Management:**
  - Create, edit, and toggle active/inactive status.
  - Automatic validation ensuring Regular Price $\ge$ Sale Price.
  - Automatic cleanup: Unlinks and deletes obsolete product images from disk upon update or deletion.
- **Landing Page Section & Color Customizer:**
  - Toggle visibility (ON/OFF) for every homepage section (Hero, Problems, Why Us, Video, Benefits, Reviews, Discount, Gallery, Order Form, Footer).
  - Real-time custom background and text color pickers with hexadecimal validation.
- **Site & SEO Settings:**
  - Upload website Logo and Favicon (automatically generates `favicon.ico` and `favicon.png`).
  - Business contact details (Phone, WhatsApp, Email, Office address).
  - Delivery charge customization for Inside/Outside Dhaka.
  - Google Analytics 4 (`G-XXXXXXXXXX`) and Meta/Facebook Pixel integration with automatic script extraction and sanitization.
- **Database-Driven SMTP Configuration:**
  - Configure SMTP host, port, username, password, and encryption directly from the admin panel without touching `.env`.
  - Built-in "Send Test Email" tool to verify mail server credentials in real time.
- **One-Click Automated Backups:**
  - Generate on-demand or scheduled ZIP archives of:
    1. MySQL Database (`.sql` dump)
    2. Uploaded Media & Images (`public/uploads`)
    3. Full System Backup (Database + Media)
  - One-click direct download and automated age-based cleanup.
- **Direct Public Uploads (cPanel Friendly):**
  - All uploaded images and media files are stored directly in `public/uploads/` instead of `storage/app/public/`.
  - **Zero symlink dependencies** (`php artisan storage:link` is NOT required on shared hosting).

---

## 💻 Tech Stack & System Requirements

### Core Stack
- **Framework:** Laravel 13.x
- **Language:** PHP 8.2 or 8.3+
- **Database:** MySQL 8.0+ / MariaDB 10.4+ (also supports PostgreSQL / SQLite)
- **Frontend UI:** Bootstrap 5.3, Bootstrap Icons, Hind Siliguri (Bengali Google Font)
- **Admin UI:** AdminLTE 3, jQuery 3.6, FontAwesome 5
- **Security & RBAC:** Spatie Laravel-Permission 6.x

### PHP Extensions Required
Verify that the following PHP extensions are enabled on your server:
- `BCMath`
- `Ctype`
- `cURL`
- `DOM` / `XML`
- `Fileinfo`
- `Filter`
- `GD` or `Imagick` (for image processing)
- `JSON`
- `Mbstring`
- `OpenSSL`
- `PDO` & `pdo_mysql`
- `Tokenizer`
- `Zip` (for full backup archives)

---

## 🚀 Quick Start (Local Development)

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/single-product-landing-website.git modhu
cd modhu
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment File
```bash
cp .env.example .env
```
Open `.env` in your text editor and configure your local MySQL database connection:
```ini
APP_NAME="খাঁটি সুন্দরবনের মধু"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=modhu_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations & Seed Dummy Data
This command resets the database, creates all tables, and seeds Spatie permissions, default admin account, 4 sample honey products, reviews, and 100+ site settings:
```bash
php artisan migrate:fresh --seed
```

### 6. Start Development Server
```bash
php artisan serve
```
Visit your store in your browser:
- **Landing Page:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Login:** [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)
- **Order Tracking:** [http://127.0.0.1:8000/track-order](http://127.0.0.1:8000/track-order)

---

## 🔑 Default Credentials

### Primary Administrator
| Field | Value |
|---|---|
| **Admin Portal URL** | `http://your-domain.com/admin/login` |
| **Email Address** | `admin@gmail.com` |
| **Password** | `password` |
| **Default Role** | `Super Admin` |

> [!NOTE]
> **Two-Factor Authentication (OTP):** When logging in locally without an SMTP server configured, check the latest line in [storage/logs/laravel.log](file:///storage/logs/laravel.log) to retrieve the 6-digit OTP code:
> ```bash
> tail -n 20 storage/logs/laravel.log
> # Output: [2026-11-21 22:45:00] local.INFO: Admin Login OTP generated for admin@modhu.com
> ```
> In the database, you can also view or copy the code from the `two_factor_code` column in the `users` table.

> [!IMPORTANT]
> **Production Notice:** Immediately upon deploying to production, log in to **Admin $\rightarrow$ Admin Accounts** and update the Super Admin email and password to your private, strong credentials!

---

## 🌐 cPanel / Shared Hosting Deployment Guide

This application is built with native compatibility for shared hosting environments (cPanel, DirectAdmin, Plesk) without requiring SSH root access or symlink permissions.

### Option A: Standard cPanel Structure (Recommended)

1. **Prepare ZIP for Upload:**
   Compress all project files into a `.zip` archive, excluding the following folders:
   - `/vendor` (or keep it if you cannot run Composer on the server)
   - `/node_modules`
   - `.git`
   - `storage/logs/*.log`

2. **Upload to cPanel File Manager:**
   - Log in to your cPanel $\rightarrow$ **File Manager**.
   - Navigate to your user home directory (one level above `public_html`, e.g. `/home/username/`).
   - Create a directory named `modhu_core` and extract your project files inside `/home/username/modhu_core/`.

3. **Move Public Assets into `public_html`:**
   - Move all files and folders inside `/home/username/modhu_core/public/` directly into your `/home/username/public_html/`.
   - Open `/home/username/public_html/index.php` and update the paths to point to `modhu_core`:
     ```php
     // Modify lines 12 & 14 to:
     require __DIR__.'/../modhu_core/vendor/autoload.php';
     $app = require_once __DIR__.'/../modhu_core/bootstrap/app.php';
     ```

4. **Create MySQL Database & User:**
   - In cPanel $\rightarrow$ **MySQL Database Wizard**:
     - Create database: `username_modhu`
     - Create user: `username_dbuser` with a strong password
     - Assign user to database with **ALL PRIVILEGES**.

5. **Configure Production `.env`:**
   In `/home/username/modhu_core/.env`, configure:
   ```ini
   APP_NAME="সুন্দরবনের মধু"
   APP_ENV=production
   APP_KEY=base64:YourGeneratedAppKeyHere=
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=username_modhu
   DB_USERNAME=username_dbuser
   DB_PASSWORD=YourStrongDatabasePassword

   FILESYSTEM_DISK=public
   ```

6. **Set Correct File Permissions:**
   In cPanel Terminal or File Manager, ensure write permissions:
   - `/home/username/modhu_core/storage/` $\rightarrow$ `775` (or `755`)
   - `/home/username/modhu_core/bootstrap/cache/` $\rightarrow$ `775` (or `755`)
   - `/home/username/public_html/uploads/` $\rightarrow$ `775` (or `755`)

7. **Migrate and Seed Database:**
   If you have **cPanel Terminal** access:
   ```bash
   cd /home/username/modhu_core
   composer install --no-dev --optimize-autoloader
   php artisan migrate --seed --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
   *If you do NOT have Terminal access:* Export your local database schema/data via phpMyAdmin and import it into cPanel phpMyAdmin.

8. **Setup Background Cron Job (for Order Throttling, OTP Cleanups & Backups):**
   In cPanel $\rightarrow$ **Cron Jobs** $\rightarrow$ Add New Cron Job (Every Minute `* * * * *`):
   ```bash
   /usr/local/bin/php /home/username/modhu_core/artisan schedule:run >> /dev/null 2>&1
   ```

---

## 🖥️ VPS / Ubuntu / Nginx Deployment Guide

For deployment on an Ubuntu VPS (DigitalOcean, AWS EC2, Linode, Hetzner, Vultr):

### 1. Install System Dependencies
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl unzip git composer -y
```

### 2. Clone & Setup Permissions
```bash
sudo mkdir -p /var/www/modhu
sudo git clone https://github.com/your-username/single-product-landing-website.git /var/www/modhu
cd /var/www/modhu

composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate

sudo chown -R www-data:www-data /var/www/modhu
sudo chmod -R 775 /var/www/modhu/storage /var/www/modhu/bootstrap/cache /var/www/modhu/public/uploads
```

### 3. Nginx Server Configuration
Create `/etc/nginx/sites-available/modhu.conf`:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/modhu/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    # Protect hidden files and uploads
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Enable site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/modhu.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 4. Enable Free SSL via Let's Encrypt
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 5. Production Optimization Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🎛️ Admin Panel & Feature Walkthrough

### 1. Store Mode Switcher (Single vs Multiple Products)
Navigate to **Admin $\rightarrow$ Products $\rightarrow$ Store Display Mode**:
- **Single Product Funnel Mode:** Focuses customer attention on one flagship honey package. Hides grid selectors and loads the selected product into the cart automatically.
- **Multiple Products Mode:** Displays honey package cards with checkboxes, quantity increasers/decreasers, and live multi-item subtotal calculations.
- **Grid Visibility Options:** Show All Products, Show Selected Packages Only, or Hide Packages Grid completely.

### 2. Orders Management & Granular Permissions
Navigate to **Admin $\rightarrow$ Orders**:
- **Status Badges:** Orders are categorized into `Pending`, `Processing`, `Delivered`, and `Cancelled`.
- **Search & Filter:** Filter order lists by status or search by customer name/phone.
- **Order Status Authorization:** Only users with the `update-order-status` permission can alter the fulfillment/payment status. Staff members with only `manage-orders` permission see a secure, read-only status box.
- **Invoice Printing:** Click the **Print** icon on any order to generate an A4 customer invoice. Add `?auto=1` to the invoice URL to automatically trigger the browser print dialogue.

### 3. Website Sections & Color Customizer
Navigate to **Admin $\rightarrow$ Sections**:
- Toggle any homepage section ON or OFF with a single click.
- Customize background colors (`#hex`) and text colors for sections including Problem/Solution, Why Choose Us, Honey Benefits, Gallery, and Footer.

### 4. Site Settings, SEO & SMTP Email
Navigate to **Admin $\rightarrow$ Settings**:
- **General Info:** Site title, tagline, phone numbers, WhatsApp link, and physical store address.
- **Delivery Charges:** Update rates for Inside Dhaka (default ৳80) and Outside Dhaka (default ৳130).
- **SEO & Social:** Meta title, meta description, Google Search Console token, Google Analytics 4 ID, Meta (Facebook) Pixel ID.
- **Email (SMTP):** Configure SMTP settings directly in the database. Use the built-in **"Send Test Email"** tool to verify server connectivity without having to place test orders.
- **Payment Gateway (SSLCommerz):** Configure your SSLCommerz Store ID (`bongo6830d3864bcb8`), Store Password, and Environment Mode (Sandbox vs Live) directly from the settings panel.

### 5. Automated Backups
Navigate to **Admin $\rightarrow$ Backups**:
- **Database Backup:** Creates a `.zip` archive containing the full `.sql` dump.
- **Files Backup:** Compresses all uploaded customer media and assets (`public/uploads`).
- **Full Backup:** Compresses both Database and Media into a single timestamped archive.
- **Download:** One-click direct secure download.

---

## 🧪 Automated Testing

The project includes an automated test suite verifying every component (checkout validation, rate-limiting, bot honeypot, Spatie RBAC permissions, 2FA OTP, backups, order tracking, and stock management).

Run all feature and unit tests with:
```bash
php artisan test
```

### Test Coverage Highlights (56 Tests, 400 Assertions)
- `test_guest_can_order_product_with_cod_and_stock_is_decremented`
- `test_guest_can_order_with_sslcommerz_and_redirects_to_pay`
- `test_sslcommerz_initiate_redirects_to_gateway_page_url`
- `test_sslcommerz_success_callback_validates_and_marks_order_paid`
- `test_sslcommerz_fail_and_cancel_callbacks`
- `test_admin_settings_can_update_sslcommerz_configuration`
- `test_cannot_order_inactive_or_out_of_stock_products`
- `test_honeypot_field_blocks_automated_bot_spam`
- `test_orders_route_rate_limiting_protects_against_spam`
- `test_order_tracking_route_rate_limiting_prevents_brute_force`
- `test_spatie_role_and_permission_enforcement`
- `test_order_status_update_permission_enforcement`
- `test_admin_two_factor_otp_login_flow_and_resend_throttle`
- `test_product_price_validation_prevents_sale_price_exceeding_regular_price`
- `test_homepage_gracefully_handles_zero_active_products`
- `test_backup_archive_generation_and_secure_download`

---

## 📂 Project Architecture & File Layout

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php               # Landing page & mode rendering
│   │   │   ├── OrderController.php              # Checkout processing & order tracking
│   │   │   └── Admin/
│   │   │       ├── AuthController.php           # Admin login, 2FA OTP & sessions
│   │   │       ├── DashboardController.php      # Sales & orders analytics
│   │   │       ├── OrderController.php          # Admin order review & invoice
│   │   │       ├── ProductController.php        # Products catalog CRUD & mode toggle
│   │   │       ├── ReviewController.php         # Customer reviews moderation
│   │   │       ├── SectionController.php        # Landing page section & color toggles
│   │   │       ├── SettingController.php        # Site settings, SEO & SMTP test mail
│   │   │       ├── UserController.php           # Admin accounts CRUD
│   │   │       ├── RoleController.php           # Spatie RBAC roles & permissions
│   │   │       └── BackupController.php         # Database & media backup manager
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php              # Admin authentication & guard
│   │   └── Requests/
│   │       └── StoreOrderRequest.php            # Server-side checkout validation
│   ├── Models/
│   │   ├── Order.php                            # Order model & order number generator
│   │   ├── OrderItem.php                        # Order line items relationship
│   │   ├── Product.php                          # Product model, image handler & scopes
│   │   ├── Review.php                           # Customer review model
│   │   ├── Setting.php                          # Key-value settings cache manager
│   │   └── User.php                             # Admin model with Spatie RBAC & 2FA
│   └── Services/
│       ├── BackupService.php                    # Full ZIP backup creation service
│       └── MailConfigService.php                # Database-driven SMTP runtime injector
├── config/
│   └── filesystems.php                          # Configured to serve public/uploads directly
├── database/
│   ├── migrations/                              # Database table blueprints
│   └── seeders/
│       └── DatabaseSeeder.php                   # Seeds roles, admin, products & 100+ settings
├── public/
│   ├── assets/                                  # Frontend CSS, JS, fonts & template images
│   ├── admin/                                   # AdminLTE 3 assets & plugins
│   └── uploads/                                 # Direct destination for all user uploads
├── resources/
│   └── views/
│       ├── home.blade.php                       # Single/Multi landing page
│       ├── order-success.blade.php              # Customer invoice confirmation
│       ├── order-track.blade.php                # Public parcel tracking
│       ├── admin/                               # AdminLTE views (Orders, Products, etc.)
│       └── emails/                              # 2FA OTP & notification email templates
├── routes/
│   └── web.php                                  # Frontend & protected admin route definitions
└── tests/
    └── Feature/
        └── OrderAndAdminTest.php                # 51 comprehensive automated test cases
```

---

## 🔒 Security & Production Hardening

Before taking your store live to receive real customer orders, verify this checklist:

1. **Environment Setup:** Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`. Leaving debug enabled exposes database credentials in error messages.
2. **Change Default Credentials:** Change the default admin email (`admin@modhu.com`) and password (`password123`) immediately.
3. **Configure SMTP Mailer:** In **Admin $\rightarrow$ Settings**, set your SMTP credentials (such as Google Workspace, Zoho Mail, Mailgun, or SendGrid) so that Admin 2FA OTP codes are delivered securely to your inbox.
4. **Enforce HTTPS:** Ensure all traffic is redirected to `https://` via Nginx/Apache configuration or Cloudflare Flexible/Full SSL.
5. **Secure Public Uploads Directory:** Ensure your web server does not execute PHP files inside the `public/uploads/` directory:
   - For Nginx, add:
     ```nginx
     location ~ ^/uploads/.*\.php$ {
         deny all;
     }
     ```
   - For Apache (`public/uploads/.htaccess`):
     ```apache
     <FilesMatch "\.(php|php5|phtml)$">
         Deny from all
     </FilesMatch>
     ```

---

## 📄 License & Support

This project is open-source software licensed under the **[MIT License](LICENSE)**.

Developed with ❤️ for honey and organic product merchants across Bangladesh. For questions, customizations, or feature requests, feel free to open an issue or pull request.
