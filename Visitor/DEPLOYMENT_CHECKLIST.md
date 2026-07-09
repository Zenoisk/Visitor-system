# AIROD Visitor Management System — Deployment Checklist

Use this guide to deploy the AIROD Visitor Management System to a staging or production server.

---

## 1. Server Requirements

Ensure the target server meets these software prerequisites:

- **PHP Version**: `8.2` or higher
- **Required PHP Extensions**:
  - `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`, `dom`
- **Database Server**: MySQL `8.0` or MariaDB `10.4` (with InnoDB transactional support)
- **Web Server**: IIS 10+, Apache 2.4+, or Nginx 1.20+
- **Process Management**: Node.js & npm (if building assets on the server)
- **Composer**: Dependency Manager for PHP

---

## 2. Upload & File Extraction

### Scenario A: Shared Hosting / Pre-Compiled Packages (No Composer/NPM on Server)
1. Run local asset compilation:
   ```bash
   npm run build
   ```
2. Compress the project folder into a ZIP archive, **excluding**:
   - `.git/`
   - `.env`
   - `database/database.sqlite` (and `-journal`)
   - `storage/logs/*.log`
   - `storage/framework/cache/*`
   - `storage/framework/views/*`
   - `.phpunit.result.cache`
3. Upload the archive and extract it in the target host directory.

### Scenario B: Dedicated Server / Cloud VM (Git & Composer available)
1. Clone the repository to the target folder:
   ```bash
   git clone <repository-url> .
   ```
2. Run production installation:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. Run npm installation and asset build:
   ```bash
   npm install
   npm run build
   ```

---

## 3. Web Server Root Safety (CRITICAL)

Ensure the web server **Document Root** points to the `/public` subfolder of the project, **not** the project root folder.

### IIS Configuration (Internet Information Services)
1. Open IIS Manager.
2. In the **Connections** panel, select your Site.
3. Click **Basic Settings...** on the right side.
4. Set the **Physical Path** to the absolute path of the `public` directory (e.g. `C:\inetpub\wwwroot\HR\public`).
5. Ensure `web.config` is present in `/public` to handle Laravel routing rewrites.

### Apache Configuration
Set the `DocumentRoot` in your virtual host configuration:
```apache
<VirtualHost *:80>
    ServerName airod-visitor.yourcompany.com
    DocumentRoot "/var/www/html/HR/public"

    <Directory "/var/www/html/HR/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx Configuration
Set the `root` directive to the `public` directory:
```nginx
server {
    listen 80;
    server_name airod-visitor.yourcompany.com;
    root /var/www/html/HR/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 4. Environment Configuration (.env)

Do **NOT** upload your development `.env` file to the production server.
1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
2. Generate a fresh application key:
   ```bash
   php artisan key:generate
   ```
3. Configure these specific production values:
   ```env
   APP_NAME="AIROD Visitor Management System"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://visitor.airod.com.my  # Change to your real server domain

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1                     # Your database server IP
   DB_PORT=3306
   DB_DATABASE=airod_visitor_management
   DB_USERNAME=your_database_user        # Do NOT use 'root' in production
   DB_PASSWORD=your_strong_password      # Use a complex generated password

   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   LOG_CHANNEL=stack
   LOG_LEVEL=error                       # Suppress debug log noise

   MAIL_MAILER=log                       # Use log or configure SMTP credentials

   # Ensure demo users are disabled
   ENABLE_DEMO_USERS=false
   ```

---

## 5. Directory Permissions

Ensure that the web server user has write permissions to the following folders:
- `storage/` (specifically `storage/app/`, `storage/framework/`, and `storage/logs/`)
- `bootstrap/cache/`

### Linux/Unix chmod Commands:
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### IIS Permissions:
Ensure the `IIS_IUSRS` or the application pool identity (e.g. `IIS AppPool\DefaultAppPool`) has full read, write, and modify permissions on `storage` and `bootstrap/cache`.

---

## 6. Laravel Production Optimization Commands

Run the following commands in the project directory to cache configuration and speed up page load:

```bash
# Clear any remaining local configuration/route caches
php artisan optimize:clear

# Run database migrations safely
php artisan migrate --force

# Create the public storage symlink for uploaded files (if needed)
php artisan storage:link

# Compile and cache configs and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7. Admin & Security User Creation

In production, since `ENABLE_DEMO_USERS=false` is set:
1. Seeders will **not** automatically create any accounts.
2. Admins and Security users must be created manually using either a CLI script, a custom registration script, or by running `php artisan tinker`:
   ```bash
   php artisan tinker
   ```
   Inside tinker, create your first admin:
   ```php
   App\Models\User::create([
       'name' => 'System Admin',
       'username' => 'airod_admin',
       'email' => 'admin@yourcompany.com',
       'password' => Hash::make('A_Very_Strong_Password_Here'),
       'role' => 'admin',
   ]);
   ```
   Create your security user:
   ```php
   App\Models\User::create([
       'name' => 'Security Gate 1',
       'username' => 'airod_security',
       'email' => 'security@yourcompany.com',
       'password' => Hash::make('A_Different_Strong_Password_Here'),
       'role' => 'security',
   ]);
   ```

---

## 8. Post-Deployment Verification Checklist

Verify these features work correctly before announcing the launch:

- [ ] **QR Code Registration Form**:
  - Open `/visitor-register` on a mobile browser.
  - Confirm the layout scale fits mobile screens.
  - Submit a registration with valid IC number.
  - Verify redirection to `/visitor-success` showing the correct visitor reference number format (e.g. `VST-YYYYMMDD-0001`).
- [ ] **Role Restrictions Test**:
  - Log in as the **security** user.
  - Confirm that "Form Builder", "Reports", "Watchlist", "Activity Logs", and "Settings" are hidden from the sidebar.
  - Verify that hitting `/admin/reports` or `/admin/settings` directly on the browser url bar returns a **403 Forbidden** page.
  - Verify that the Dashboard quick action grid shows only "All Visitors", "QR Code", "Emergency List", and "Watchlist" (view-only).
  - Attempt to delete a visitor record and confirm the button is absent.
- [ ] **Check-in / Check-out Workflow**:
  - Log in as **security** or **admin**.
  - Approve a pending visitor.
  - Check in the visitor and verify they appear immediately on the **Emergency List** (currently inside).
  - Check out the visitor and verify they are removed from the Emergency List.
- [ ] **Web Server Safety Audit**:
  - Access `https://yourdomain.com/.env` on a browser. Ensure it returns a **403 Forbidden** or **404 Not Found** instead of rendering the environment file.
  - Ensure `APP_DEBUG=false` is active (check that runtime errors do not reveal code stack traces).
