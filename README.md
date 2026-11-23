# 🍯 খাঁটি সুন্দরবনের মধু (Khanti Sundarban Modhu)
### Premium E-Commerce Landing Page & Full-Featured Admin Back-Office

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Payment Gateway](https://img.shields.io/badge/SSLCommerz-Official%20PHP%20Integrated-008080?style=for-the-badge&logo=visa&logoColor=white)](https://sslcommerz.com)
[![Deployment](https://img.shields.io/badge/Shared%20Hosting-cPanel%20Ready-F1A80A?style=for-the-badge&logo=cpanel&logoColor=white)](https://cpanel.net)
[![Build Status](https://img.shields.io/badge/Production-Optimized%20(Zero%20Bloat)-success?style=for-the-badge&logo=rocket&logoColor=white)](#-tech-stack--system-requirements)

A complete, ultra-high-converting single and multi-product e-commerce landing page built specifically for honey, organic foods, and direct-to-consumer (D2C) businesses in Bangladesh. Powered by **Laravel 13**, **Bootstrap 5**, and **AdminLTE 3**, featuring **Cash on Delivery (COD)** + **SSLCommerz (bKash, Nagad, Rocket, Upay, Visa, MasterCard)** payment gateways, live parcel tracking, multi-layer bot & spam protection, Spatie Role-Based Access Control (RBAC), Admin 2FA (Email OTP), custom admin portal URL (`/secure-portal`), re-authentication on password changes, 1-hour inactivity auto-logout, on-demand media & database backups, print-ready customer invoices, full order deletion management, and offline asymmetric node signature authorization.

---

## 📑 Table of Contents
1. [Key Features](#-key-features)
2. [Tech Stack & System Requirements](#-tech-stack--system-requirements)
3. [Quick Start (Local Development)](#-quick-start-local-development)
4. [Default Credentials & Admin Security](#-default-credentials--admin-security)
5. [Node Authorization (License Key) Management & Pre-Ship Guide](#-node-authorization-license-key-management--pre-ship-guide)
6. [SSLCommerz Payment Gateway Setup](#-sslcommerz-payment-gateway-setup)
7. [cPanel / Shared Hosting Upload & Deployment Guide](#-cpanel--shared-hosting-upload--deployment-guide)
8. [VPS / Ubuntu / Nginx Deployment Guide](#-vps--ubuntu--nginx-deployment-guide)
9. [Admin Panel & Feature Walkthrough](#-admin-panel--feature-walkthrough)
10. [Print-Friendly Dynamic Invoicing](#-print-friendly-dynamic-invoicing)
11. [Project Architecture & File Layout](#-project-architecture--file-layout)
12. [Security & Production Hardening Checklist](#-security--production-hardening-checklist)
13. [License & Support](#-license--support)

---

## 🌟 Key Features

### 🛍️ Customer Frontend (High-Converting Landing Page)
- **Single & Multi-Product Mode (Funnel Switcher):** Easily switch between a laser-focused Single Product Landing Funnel or an interactive Multi-Product Honey Package Picker from the Admin Panel with a single click.
- **Dual Payment Methods:**
  - **Cash on Delivery (COD):** Zero upfront payment; order is confirmed instantly.
  - **Online Payment (SSLCommerz):** Instant checkout via bKash, Nagad, Rocket, Upay, Visa, MasterCard, and DBBL Nexus.
- **Robust Payment Lifecycle & Customer Recovery:**
  - Automated hosted gateway session initiation matching the official SSLCommerz PHP standard with full shipping & item payloads.
  - Unpaid online orders are **strictly guarded** and never confirmed as completed until payment is validated.
  - Dedicated **Payment Status & Recovery Page** (`/orders/{order}/payment-status`): If card/bKash payment fails or the customer cancels, they can immediately retry online payment or click **"ক্যাশ অন ডেলিভারিতে (COD) অর্ডার কনফার্ম করুন"** to convert the order to COD.
- **Dynamic Delivery Charge Calculation:** Instant toggle between "Inside Dhaka" (৳80 default) and "Outside Dhaka" (৳130 default) with live JavaScript total calculation.
- **Public Order Tracking (`/track-order`):** Customers can track their real-time parcel fulfillment and delivery status using their Order ID (e.g. `ORD-261122-16D285`) and Phone Number in Bangladesh Standard Time (BST / GMT+6).
- **Stock & Availability Safeguards:** Prevents checkout of out-of-stock or unpublished products; notifies customers if an item is sold out.
- **SEO & Social Sharing Ready:** Open Graph, Twitter Cards, Google Search Console meta tag, dynamic JSON-LD Product & Organization Schema, canonical URLs, and dynamic sitemap (`/sitemap.xml`).
- **Speed & Cache Busting:** Automatically appends cache-busting version hashes to CSS/JS so design updates reflect immediately across customer browsers.

### 🛡️ Bot & Spam Protection
- **Anti-Bot Invisible Honeypot:** Traps automated submission scripts before they hit the database.
- **Order Rate Limiter (`throttle:orders`):** Strict 10 orders/minute limit per IP with friendly Bengali warning messages to prevent checkout flooding.
- **Payment & Tracking Rate Limiter (`throttle:15,1` / `throttle:order-tracking`):** Prevents automated brute-force scraping and payment session flooding.
- **Double-Click Submit Protection:** Disables submit buttons and displays animated loading spinners upon submission to prevent duplicate orders.

### 🔐 Admin Back-Office (AdminLTE 3)
- **Analytics Dashboard:** Total orders, revenue, pending vs delivered charts, and customer overview.
- **Custom Admin Portal URL (`/secure-portal`):** Standard `/admin/login` and `/login` routes return `404 Not Found`, completely concealing the login entrance from automated scanner bots.
- **Order Management, Deletion & Invoicing:**
  - View full order history, filter by fulfillment status (`Pending`, `Processing`, `Delivered`, `Cancelled`).
  - **One-Click Order Deletion:** Dedicated delete option on order list, order details, and dashboard with confirmation modals that cleanly cascades and deletes associated items.
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
- **Re-Authentication on Password Change:**
  - Administrators must provide their current password to re-authenticate before saving a new password on their account.
- **Automatic Inactivity Logout (1 Hour):**
  - Admins are automatically logged out after 60 minutes (3,600 seconds) of inactivity to prevent unauthorized session hijacking.
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
  - Configure SMTP host, port, username, password, and encryption directly from the admin panel without editing `.env`.
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
- **Language:** PHP 8.2, 8.3, or 8.4+
- **Database:** MySQL 8.0+ / MariaDB 10.4+ (also supports PostgreSQL & SQLite)
- **Timezone:** Configured for **Asia/Dhaka (GMT+6 / Bangladesh Standard Time)** across app, database, and blade templates.
- **Frontend UI:** Bootstrap 5.3, Bootstrap Icons, Hind Siliguri (Bengali Google Font), Inter (English)
- **Admin UI:** AdminLTE 3, jQuery 3.6, FontAwesome 5
- **Security & RBAC:** Spatie Laravel-Permission 6.x
- **Production Build:** Fully clean Composer footprint with **zero development dependencies** for maximum performance and security.

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
- `Sodium` (for cryptographic node authorization)
- `Tokenizer`
- `Zip` (for system & database backups)

---

## 🚀 Quick Start (Local Development)

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/single-product-landing-website.git modhu
cd modhu
```

### 2. Install Dependencies
```bash
composer install --optimize-autoloader
```

### 3. Setup Environment File
```bash
cp .env.example .env
```
Open `.env` in your text editor and configure your local MySQL database connection and settings:
```ini
APP_NAME="খাঁটি সুন্দরবনের মধু"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Dhaka
DB_TIMEZONE="+06:00"

# Node Authorization Signature (Generate using php tools/issue_token.php)
APP_NODE_SIG=eyJyZWYiOiJjdXN0b21lci0wMSIsImlhdCI6MTc5NTQyNTgwMCwiZXhwIjowLCJub25jZSI6IjdjNWJmZTg5MGM4NDg3MTcifQ.2keA9RuPTyXqD0NtHA4m3qC_6yk7ZaFi1wQ5whFIrK9x-f1b7059tQjKTnsbyOLHSM9y-Vwr56U1ylsZxMxOCw

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
This command creates all database tables, seeds Spatie roles & permissions, creates the default Super Admin account, 4 sample honey products, real customer reviews, and 100+ site settings:
```bash
php artisan migrate:fresh --seed
```

### 6. Start Development Server
```bash
php artisan serve
```
Visit your store in your browser:
- **Landing Page:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Portal:** [http://127.0.0.1:8000/secure-portal](http://127.0.0.1:8000/secure-portal) *(Default `/admin/login` is hidden with 404)*
- **Order Tracking:** [http://127.0.0.1:8000/track-order](http://127.0.0.1:8000/track-order)

---

## 🔑 Default Credentials & Admin Security

### Primary Administrator
| Field | Value |
|---|---|
| **Custom Admin Portal URL** | `http://yourdomain.com/secure-portal` |
| **Email Address** | `admin@gmail.com` |
| **Password** | `password` |
| **Default Role** | `Super Admin` |

> [!NOTE]
> **Two-Factor Authentication (OTP):** When logging in locally without an SMTP server configured, check the latest line in [storage/logs/laravel.log](file:///storage/logs/laravel.log) to retrieve the 6-digit OTP code:
> ```bash
> tail -n 20 storage/logs/laravel.log
> # Output: [2026-11-23 15:30:00] local.INFO: Admin Login OTP generated for admin@gmail.com: 123456
> ```
> In the database, you can also view or copy the code from the `two_factor_code` column in the `users` table.

> [!IMPORTANT]
> **Security Checklist for Administrators:**
> 1. **Change Password with Re-Authentication:** Go to **Admin $\rightarrow$ Users $\rightarrow$ Edit**. Enter your current password in the **Current Password** field before setting a new strong password.
> 2. **Automatic 1-Hour Logout:** If the admin session is inactive for 60 minutes, the session is invalidated and redirected to `/secure-portal` automatically.
> 3. **Hidden Admin Entrance:** Do not share the `/secure-portal` URL publicly.

---

## 🔐 Node Authorization (License Key) Management & Pre-Ship Guide

The project includes an **asymmetric, 100% offline authorization system** using **Ed25519 (PHP Sodium)**.

### How It Works & Why Customers Cannot Generate Keys
1. **Asymmetric Keypair:** The local generator (`tools/issue_token.php`) holds the **Private Signing Key**. The Laravel application codebase holds only the **Public Verification Key** (`app/Support/CoreNodeVerifier.php`).
2. **Mathematical Impossibility:** In Ed25519 cryptography, possessing the public key allows someone to *verify* that a token was signed by you, but mathematically **prevents them from generating or forging a valid signature**.
3. **Standalone Generator:** The generator script lives in `tools/` completely separate from Laravel. You run it on your machine to create a key, paste it into the customer's `.env`, and then **delete `tools/` before shipping**.

---

### Step-by-Step: Issuing a Key to a Customer

From your local computer terminal, run the standalone tool:

#### 1. Issue a Lifetime / Perpetual Key
```bash
php tools/issue_token.php "customer-company-name"
```

#### 2. Issue a Timed Key (e.g. 1 Year or 30 Days)
```bash
# Valid for 365 days from now
php tools/issue_token.php "customer-company-name" --days=365

# Valid for 30 days trial
php tools/issue_token.php "customer-company-name" --days=30
```

#### 3. Issue a Key with a Specific Expiry Date
```bash
php tools/issue_token.php "customer-company-name" --expires="2027-12-31"
```

#### Generator Terminal Output:
```text
================================================================================
                 NODE ENVIRONMENT SIGNATURE GENERATED                           
================================================================================
 Customer / Node Ref: customer-company-name
 Issued At:           2026-11-23 15:30:00 UTC
 Expiration:          Perpetual / Lifetime (No Expiration)
 Public Key (Verify): 724bfd2bc7c18ddd32c98ab5753af561ce514de7cba208701973d2b296dbd822
--------------------------------------------------------------------------------
 Copy & place this line into the target .env file:

APP_NODE_SIG=eyJyZWYiOiJjdXN0b21lci1jb21wYW55LW5hbWUiLCJpYXQiOjE3OTU0MjU4MDAsImV4cCI6MCwibm9uY2UiOiI3YzViZmU4OTBhODQ4NzE3In0.2keA9RuPTyXqD0NtHA4m3qC_6yk7ZaFi1wQ5whFIrK9x-f1b7059tQjKTnsbyOLHSM9y-Vwr56U1ylsZxMxOCw

================================================================================
```

Copy the generated `APP_NODE_SIG=...` line into the client's `.env` file.

---

### Step-by-Step: Deleting the Generator Before Shipping to Customer

To ensure the customer cannot generate their own keys, remove the generator folder completely before packaging the project:

#### 1. Delete the `tools/` Folder
**On Windows PowerShell:**
```powershell
Remove-Item -Recurse -Force tools
```
**On Linux / macOS:**
```bash
rm -rf tools/
```

#### 2. Verify Zero Stray References Remain
Confirm that `app/`, `config/`, `routes/`, and `bootstrap/` contain no references to `tools`:
**On Windows PowerShell:**
```powershell
Get-ChildItem -Path app, config, routes, bootstrap -Recurse | Select-String "tools|issue_token"
```
**On Linux / Git Bash:**
```bash
grep -rnE "(tools|issue_token)" app/ config/ routes/ bootstrap/
```
*(The command will return zero results, confirming 100% clean decoupling).*

---

### 24-Hour Cache & Instant Cache-Bust
- The validation result is cached in Laravel for **24 hours (86,400 seconds)** under `sys_node_state_<md5_hash_of_token>`.
- The evaluation runs in `< 0.1ms` for regular requests.
- If a customer updates or renews their `APP_NODE_SIG` in `.env`, the MD5 hash changes instantly, bypassing the old cache and running an immediate check.
- If the token is missing, invalid, or expired, the app returns a generic **503 Service Unavailable** (`ERR_NODE_CONFIG_REF_503`) without leaking cryptographic details.

---

## 💳 SSLCommerz Payment Gateway Setup

The application features full, production-ready integration with Bangladesh's premier payment aggregator **SSLCommerz**, enabling instant online payments via bKash, Nagad, Rocket, Upay, Visa, MasterCard, and DBBL Nexus.

### 1. Configuration (Admin Panel or `.env`)
You can manage credentials directly from the browser without modifying code:
- Go to **Admin $\rightarrow$ Site & SEO Settings $\rightarrow$ Tab 12: SSLCommerz Payment Gateway**:
  - **Enable SSLCommerz:** Set to `Enabled (হ্যাঁ)`.
  - **Environment Mode:** Select `Sandbox (টেস্টিং)` for demo testing or `Live / Production (বাস্তব লেনদেন)` for real money.
  - **Store ID:** Enter your SSLCommerz Store ID (e.g. `bongo6830d3864bcb8`).
  - **Store Password:** Enter your SSLCommerz Store Password (e.g. `bongo6830d3864bcb8@ssl`).
  - Click **সেটিংস সংরক্ষণ করুন (Save Settings)**.

You can also specify defaults in your `.env` file:
```ini
SSLCOMMERZ_STORE_ID=bongo6830d3864bcb8
SSLCOMMERZ_STORE_PASSWORD=bongo6830d3864bcb8@ssl
SSLCOMMERZ_IS_SANDBOX=true
SSLCOMMERZ_IS_LOCALHOST=true
```

### 2. How the Payment Flow Operates
1. **Initiation (`/orders/{order}/pay`):** When a customer submits checkout with "অনলাইন পেমেন্ট (SSLCommerz)", an order is generated with status `unpaid`. The system builds a full, compliant parameter payload (customer details, delivery address, shipping method, and item summary) and connects to SSLCommerz API to generate a secure `GatewayPageURL`.
2. **Payment Processing:** The customer is redirected to SSLCommerz to enter PIN / OTP for bKash, Nagad, or cards.
3. **Success Callback (`/sslcommerz/success`):** Upon payment completion, SSLCommerz redirects the customer back. The server verifies the transaction with SSLCommerz's Order Validation Server API (`orderValidate`).
   - If verified: `$order->payment_status` is updated to `'paid'`, transaction ID and card type are recorded, and the customer is redirected to the order confirmation page.
   - If unverified/tampered: The order is marked `'failed'` and customer is redirected to the payment status recovery page.
4. **Failure & Cancellation (`/sslcommerz/fail` & `/sslcommerz/cancel`):**
   - If payment fails (e.g. insufficient balance) or customer clicks Cancel, they are redirected to `/orders/{order}/payment-status`.
   - The page offers two clear actions:
     - 💳 **"পুনরায় অনলাইন পেমেন্ট করুন" (Retry Online Payment):** Re-attempts payment with SSLCommerz.
     - 📦 **"ক্যাশ অন ডেলিভারিতে (COD) অর্ডার কনফার্ম করুন" (Convert to COD):** Converts the order payment method to COD and confirms the order immediately.
5. **Instant Payment Notification (IPN Webhook):**
   - URL: `https://yourdomain.com/sslcommerz/ipn`
   - SSLCommerz server-to-server notifications are automatically validated and processed asynchronously.
   - All `/sslcommerz/*` callback routes are pre-configured to bypass CSRF token verification in [bootstrap/app.php](file:///bootstrap/app.php).

---

## 🌐 cPanel / Shared Hosting Upload & Deployment Guide

This application is built with native compatibility for shared hosting environments (cPanel, DirectAdmin, Plesk) without requiring SSH root access or symlink permissions.

### Step 1: Prepare the Project Archive (.zip)
1. Generate the customer's `APP_NODE_SIG` token on your local machine using `php tools/issue_token.php "customer-name"`.
2. Delete the `tools/` folder:
   ```bash
   rm -rf tools/
   ```
3. Create a `.zip` file of the project root:
   - **Include:** `app`, `bootstrap`, `config`, `database`, `lang`, `public`, `resources`, `routes`, `storage`, `vendor`, `.env.example`, `artisan`, `composer.json`.
   - **Exclude:** `.git`, `node_modules`, `storage/logs/*.log`, `tools/`.

> [!TIP]
> Because development dependencies have already been removed from Composer, the `/vendor` folder is compact, clean, and ready to upload directly without needing to run Composer on your cPanel.

### Step 2: Upload Files to cPanel
1. Log in to your cPanel dashboard.
2. Open **File Manager**.
3. In your user home directory (one level above `public_html`, e.g. `/home/yourusername/`), create a folder named `modhu_core`.
4. Upload your `.zip` archive into `/home/yourusername/modhu_core/` and extract it.

### Step 3: Setup Web Root (`public_html`)
1. In cPanel File Manager, enter `/home/yourusername/modhu_core/public/`.
2. Select **all files and folders** inside `public` (including `.htaccess`, `index.php`, `assets/`, `admin/`, `uploads/`, `favicon.ico`).
3. Move them directly into `/home/yourusername/public_html/`.
4. Open `/home/yourusername/public_html/index.php` in the cPanel Code Editor.
5. Update lines 12 & 14 to reference your `modhu_core` directory:
   ```php
   // Change from:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';

   // Change to:
   require __DIR__.'/../modhu_core/vendor/autoload.php';
   $app = require_once __DIR__.'/../modhu_core/bootstrap/app.php';
   ```
6. Save the file.

### Step 4: Create MySQL Database & User
1. In cPanel, navigate to **MySQL® Databases** (or **MySQL Database Wizard**).
2. Create a new database, e.g. `youruser_modhudb`.
3. Create a new database user, e.g. `youruser_modhuuser` with a strong password.
4. Add the user to the database and check **ALL PRIVILEGES**.

### Step 5: Configure Production `.env`
1. In cPanel File Manager, navigate to `/home/yourusername/modhu_core/`.
2. Copy `.env.example` to `.env` (make sure "Show Hidden Files" is enabled in cPanel File Manager settings).
3. Edit `.env` with your production settings:
   ```ini
   APP_NAME="খাঁটি সুন্দরবনের মধু"
   APP_ENV=production
   APP_KEY=base64:YourGeneratedAppKeyHere=
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   APP_TIMEZONE=Asia/Dhaka
   DB_TIMEZONE="+06:00"

   # Customer Node Signature Key (Generated via tools/issue_token.php)
   APP_NODE_SIG=eyJyZWYiOiJjdXN0b21lci0wMSIsImlhdCI6MTc5NTQyNTgwMCwiZXhwIjowLCJub25jZSI6IjdjNWJmZTg5MGM4NDg3MTcifQ.2keA9RuPTyXqD0NtHA4m3qC_6yk7ZaFi1wQ5whFIrK9x-f1b7059tQjKTnsbyOLHSM9y-Vwr56U1ylsZxMxOCw

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=youruser_modhudb
   DB_USERNAME=youruser_modhuuser
   DB_PASSWORD=YourStrongDatabasePassword

   FILESYSTEM_DISK=public
   ```

### Step 6: Import Database Schema & Seed Data
Choose either of the two methods below:

- **Method A: Via cPanel Terminal (Fastest):**
  ```bash
  cd /home/yourusername/modhu_core
  php artisan migrate --seed --force
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- **Method B: Via phpMyAdmin (No Terminal Required):**
  1. Export your local database from your local phpMyAdmin or MySQL client into a `.sql` file.
  2. In cPanel, open **phpMyAdmin**, select `youruser_modhudb`, and click **Import**.
  3. Upload your `.sql` file and click **Go**.

### Step 7: Set File & Directory Permissions
Ensure the following directories have write permissions:
- `/home/yourusername/modhu_core/storage/` $\rightarrow$ `775` (or `755`)
- `/home/yourusername/modhu_core/bootstrap/cache/` $\rightarrow$ `775` (or `755`)
- `/home/yourusername/public_html/uploads/` $\rightarrow$ `775` (or `755`)

### Step 8: Setup Cron Job for Automation
In cPanel $\rightarrow$ **Cron Jobs** $\rightarrow$ Add New Cron Job (Every Minute `* * * * *`):
```bash
/usr/local/bin/php /home/yourusername/modhu_core/artisan schedule:run >> /dev/null 2>&1
```

---

## 🖥️ VPS / Ubuntu / Nginx Deployment Guide

For deployment on an Ubuntu VPS (DigitalOcean, AWS EC2, Linode, Hetzner, Vultr):

### 1. Install System Dependencies
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring \
php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl unzip git composer -y
```

### 2. Clone & Setup Permissions
```bash
sudo mkdir -p /var/www/modhu
sudo git clone https://github.com/your-username/single-product-landing-website.git /var/www/modhu
cd /var/www/modhu

composer install --optimize-autoloader
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

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Protect uploaded files directory from PHP script execution
    location ~ ^/uploads/.*\.php$ {
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

### 2. Orders Management, Deletion & Permissions
Navigate to **Admin $\rightarrow$ Orders**:
- **Status Badges:** Orders are categorized into `Pending`, `Processing`, `Delivered`, and `Cancelled`.
- **Order Deletion:** Click the red **Trash** icon on any order in the orders list, dashboard, or order detail page to permanently delete spam/test orders. Associated items are cleanly deleted automatically.
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
- **Payment Gateway (SSLCommerz):** Configure your SSLCommerz Store ID, Store Password, and Environment Mode (Sandbox vs Live) directly from the settings panel.

### 5. Automated Backups
Navigate to **Admin $\rightarrow$ Backups**:
- **Database Backup:** Creates a `.zip` archive containing the full `.sql` dump.
- **Files Backup:** Compresses all uploaded customer media and assets (`public/uploads`).
- **Full Backup:** Compresses both Database and Media into a single timestamped archive in Bangladesh Standard Time (GMT+6).
- **Download:** One-click direct secure download.

---

## 📄 Print-Friendly Dynamic Invoicing

The customer invoice template ([invoice.blade.php](file:///resources/views/admin/orders/invoice.blade.php)) is engineered to produce clean, professional printouts on standard **A4 paper**:

### Key Invoicing Highlights
- **Dynamic Payment Logic:**
  - **When Paid Online (SSLCommerz):**
    - Displays a prominent green **PAID ONLINE** stamp.
    - Courier warning notice: *"This parcel has already been PAID IN FULL. Do NOT collect any cash from customer upon delivery."*
    - Total calculation: Subtotal + Delivery = Total, minus Paid Online = **Due / Amount to Collect: ৳0.00**.
  - **When Cash on Delivery (COD):**
    - Clear delivery notice: *"Please collect ৳[Total] in cash from customer upon parcel delivery."*
    - Highlights **Total to Collect (COD): ৳[Total]**.
- **Clean Customer Presentation:**
  - Sensitive payment gateway internals (Transaction IDs, Card types, Bank references) and manual signature placeholders are excluded for a clean, professional finish.
- **Print Optimization (`@media print`):**
  - Configured for standard A4 portrait (`@page { size: A4 portrait; margin: 12mm 15mm; }`).
  - Automatically hides action buttons (`.no-print`) and outer shadows.
  - Retains background colors and highlight badges via `-webkit-print-color-adjust: exact`.
  - Avoids awkward table row breaks with `page-break-inside: avoid`.
  - Automatically launches the print dialog when visited with `?auto=1`.

---

## 📂 Project Architecture & File Layout

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php               # Landing page & funnel switcher
│   │   │   ├── OrderController.php              # Checkout processing & order tracking
│   │   │   ├── SSLCommerzPaymentController.php  # SSLCommerz hosted initiation & callbacks
│   │   │   └── Admin/
│   │   │       ├── AuthController.php           # Admin login, 2FA OTP, custom /secure-portal
│   │   │       ├── DashboardController.php      # Sales & orders analytics
│   │   │       ├── OrderController.php          # Admin order review, deletion & A4 invoices
│   │   │       ├── ProductController.php        # Products catalog CRUD & mode toggle
│   │   │       ├── ReviewController.php         # Customer reviews moderation
│   │   │       ├── SectionController.php        # Landing page section & color toggles
│   │   │       ├── SettingController.php        # Site settings, SEO & SMTP test mail
│   │   │       ├── UserController.php           # Admin accounts CRUD with re-auth check
│   │   │       ├── RoleController.php           # Spatie RBAC roles & permissions
│   │   │       └── BackupController.php         # Database & media backup manager
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php              # Admin authentication & 1-hr inactivity timeout
│   │   │   └── EnsureNodeEnvironment.php        # Stealth offline Ed25519 node check
│   │   └── Requests/
│   │       └── StoreOrderRequest.php            # Server-side checkout validation
│   ├── Library/
│   │   └── SslCommerz/
│   │       ├── SslCommerzInterface.php          # Payment gateway contract
│   │       ├── AbstractSslCommerz.php           # cURL transport, API URLs & SSL checks
│   │       └── SslCommerzNotification.php       # Payload builder & orderValidate logic
│   ├── Models/
│   │   ├── Order.php                            # Order model & order number generator (BST)
│   │   ├── OrderItem.php                        # Order line items relationship
│   │   ├── Product.php                          # Product model, image handler & scopes
│   │   ├── Review.php                           # Customer review model
│   │   ├── Setting.php                          # Key-value settings cache manager
│   │   └── User.php                             # Admin model with Spatie RBAC, OTP & 2FA
│   ├── Services/
│   │   ├── BackupService.php                    # Full ZIP backup creation service (BST)
│   │   └── MailConfigService.php                # Database-driven SMTP runtime injector
│   └── Support/
│       └── CoreNodeVerifier.php                 # Ed25519 public key validator & 24h cache
├── config/
│   ├── app.php                                  # Timezone (Asia/Dhaka) & APP_NODE_SIG mapping
│   ├── database.php                             # MySQL session timezone (+06:00)
│   ├── filesystems.php                          # Configured to serve public/uploads directly
│   └── sslcommerz.php                           # SSLCommerz gateway endpoints & credentials
├── database/
│   ├── migrations/                              # Database table blueprints
│   ├── seeders/
│   │   └── DatabaseSeeder.php                   # Seeds roles, admin, products & 100+ settings
│   └── factories/
│       └── UserFactory.php                      # Model factories
├── public/
│   ├── assets/                                  # Frontend CSS, JS, fonts & template images
│   ├── admin/                                   # AdminLTE 3 assets & plugins
│   └── uploads/                                 # Direct destination for all user uploads
├── resources/
│   └── views/
│       ├── home.blade.php                       # Single/Multi landing page
│       ├── order-success.blade.php              # Customer order confirmation memo
│       ├── order-payment-status.blade.php       # Payment retry / Convert to COD page
│       ├── order-track.blade.php                # Public parcel tracking (BST / GMT+6)
│       ├── admin/                               # AdminLTE views (Orders, Products, etc.)
│       │   └── orders/
│       │       ├── invoice.blade.php            # Print-ready A4 customer invoice
│       │       └── show.blade.php               # Detailed order view & status update
│       └── emails/                              # 2FA OTP & notification email templates
├── routes/
│   └── web.php                                  # Frontend, /secure-portal & admin routes
├── storage/                                     # Logs, framework cache & backups
├── tools/                                       # Standalone key generator (Keep local, delete before shipping)
│   └── issue_token.php                          # Generates customer APP_NODE_SIG keys
└── composer.json                                # Production-only Composer dependencies
```

---

## 🔒 Security & Production Hardening Checklist

Before taking your store live to receive real customer orders, verify this checklist:

1. **Environment Setup:** Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`. Leaving debug enabled exposes database credentials in error messages.
2. **Node Authorization Key:** Ensure a valid `APP_NODE_SIG` key is present in `.env`.
3. **Delete Tools Directory:** Delete the `tools/` directory before delivering code to customers.
4. **Change Default Credentials:** Log in at `/secure-portal` and update the default admin email (`admin@gmail.com`) and password (`password`) immediately. You will be prompted to enter the current password to re-authenticate.
5. **Configure SMTP Mailer:** In **Admin $\rightarrow$ Settings**, set your SMTP credentials (such as Google Workspace, Zoho Mail, Mailgun, or SendGrid) so that Admin 2FA OTP codes are delivered securely to your inbox.
6. **SSLCommerz Live Mode:** When ready for actual payments, switch Environment Mode to `Live` in **Admin $\rightarrow$ Settings $\rightarrow$ SSLCommerz** and enter your Live Store ID & Store Password.
7. **Enforce HTTPS:** Ensure all traffic is redirected to `https://` via Nginx/Apache configuration or Cloudflare Flexible/Full SSL.
8. **Secure Public Uploads Directory:** Ensure your web server does not execute PHP files inside the `public/uploads/` directory:
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

This project is proprietary software developed for honey and organic product merchants in Bangladesh.

Developed with ❤️ for direct-to-consumer e-commerce businesses.
