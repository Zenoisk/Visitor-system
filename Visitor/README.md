# AIROD Visitor Management System

A QR-based digital visitor registration and security tracking system built for AIROD. Replaces the traditional paper visitor logbook with a modern, web-based solution.

## Features

### Public-Facing
- **QR Code Registration** — Visitors scan a QR code, fill in an online form, and submit their details.
- **Visitor Number** — Unique reference number (e.g. `VST-20260604-0001`) generated on every submission.
- **Passport Support** — Accepts both Malaysian IC numbers and international passport formats.

### Admin Dashboard
- **6 Stat Cards** — Total, Pending, Approved, Checked In, Checked Out, Currently Inside.
- **Currently Inside** — Live section showing visitors who have checked in but not yet checked out.
- **Quick Actions** — One-click links to common tasks.

### Visitor Management
- **Smart Status Buttons** — Context-aware action buttons based on the visitor's current status:
  - Pending → Approve / Reject
  - Approved → Check In
  - Checked In → Check Out
- **Status Transition Enforcement** — Invalid transitions (e.g. checked out → checked in) are blocked.
- **Quick Filter Tabs** — Today, Pending, Approved, Inside, Checked Out, All.
- **Search** — Search by name, visitor number, IC, company, or host.

### Security
- **Watchlist / Blacklist** — Flag IC/Passport numbers. Matching registrations are silently marked as blacklisted on the admin side (the public form still shows "success" to avoid confrontation).
- **Role-Based Access** — Admin (full CRUD, delete, form builder) vs Security (view, check-in/out, no delete).
- **Emergency On-Site List** — Print-friendly list of all currently checked-in visitors.

### Reporting
- **Daily Reports** — Filter by date range and status with summary stats.
- **Excel Export** — Download filtered visitor data as a styled `.xlsx` file.

### Audit
- **Activity Logs** — Full audit trail of all actions (create, update, delete, status changes).

### Administration
- **QR Code Generator** — Locally generated SVG QR code with print button.
- **Custom Form Builder** — Add custom fields to the public registration form (admin only).
- **Settings** — Update admin name, email, and password.

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL |
| Frontend | Blade + Tailwind CSS v4 |
| Build Tool | Vite |
| Auth | Custom session-based (admin/security roles) |
| QR Code | `simplesoftwareio/simple-qrcode` |
| Excel | `maatwebsite/excel` |

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Node.js 18+ & npm
- Composer

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd HR

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# DB_DATABASE=airod_visitor_management
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed default users
php artisan db:seed

# Build assets
npm run build
# Or for development:
npm run dev

# Start the server
php artisan serve
```

## Default Users

| Role | Email | Password |
|---|---|---|
| Admin | `admin@airod.test` | `password` |
| Security | `security@airod.test` | `password` |

## Key Routes

| URL | Description |
|---|---|
| `/visitor-register` | Public registration form (QR target) |
| `/visitor-success` | Registration success page |
| `/login` | Admin/Security login |
| `/admin/dashboard` | Admin dashboard |
| `/admin/visitors` | Visitor records |
| `/admin/qr-code` | QR code page |
| `/admin/reports` | Daily reports + Excel export |
| `/admin/emergency` | Emergency on-site list |
| `/admin/watchlists` | Security watchlist |
| `/admin/activity-logs` | System audit trail |
| `/admin/settings` | Profile & password settings |

## Project Structure

```
app/
├── Exports/            # Excel export classes
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # Dashboard, Visitor, QR, Report, Emergency, Watchlist, Settings, ActivityLog
│   │   ├── Auth/       # Login/Logout
│   │   └── PublicVisitorRegistrationController.php
│   ├── Middleware/      # EnsureAdminRole
│   └── Requests/       # Form validation
├── Models/             # Visitor, User, Watchlist, ActivityLog, VisitorFormField
└── Services/           # QrCodeService, VisitorNumberService, ActivityLogService

resources/views/
├── admin/              # All admin panel views
├── auth/               # Login page
├── components/         # Blade components (status-badge)
├── layouts/            # Admin + Guest layouts
└── visitor-registration/  # Public form + thank-you page
```

## Production Notes

For production deployment:
1. **Web Root Configuration**: Ensure the web server document root points to the `/public` directory of the project, not the project root, to protect sensitive source files (e.g. `.env`, configuration, databases).
2. **Environment File**: Do not upload the local `.env` file to production. Copy `.env.example` as a template and configure it with secure production values (database credentials, queue driver, session driver).
3. **Seeding configuration**: Set `ENABLE_DEMO_USERS=false` in your production environment to prevent seeding weak default accounts. Use environment variables `DEMO_ADMIN_*` and `DEMO_SECURITY_*` if staging seeding is required.
4. **Caching Optimizations**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```
5. **Asset Compilation**: Build the frontend assets for production:
   ```bash
   npm run build
   ```

## Security Notes

1. **Role-Based Access Control (RBAC)**:
   - **Admin role**: Access to dashboard, visitor CRUD, reports (including Excel export), activity logs, watchlist management, form builder, and system settings.
   - **Security role**: Access to dashboard, visitor list, visitor details, status transitions (check-in/check-out), QR code viewing, and emergency list. Completely blocked from deletion, settings, and administration tools.
2. **Unique Visitor Number Guarantee**: Uses transaction-wrapped database retries when generating visitor sequential numbers to guarantee no duplicate collisions under concurrent registration requests.
3. **Watchlist Screening**: Screenings are executed on visitor registration. Flagged visitors are registered successfully on the guest-facing page but silently flagged with a red alert on the admin dashboard.

## Troubleshooting

- **Symlink creation issues**: If public images or uploads do not load, delete the `public/storage` directory and run:
  ```bash
  php artisan storage:link
  ```
- **Permission errors**: Ensure that the `storage/` and `bootstrap/cache/` directories are writable by the web server user (`www-data`, `IUSR`, etc.).
- **Vite asset compilation**: If static assets or Tailwind styling does not load on the server, ensure `npm run build` has run and the files exist in `public/build`.
- **Database locks**: If the database throws sequence collision errors, make sure you are using a database engine that supports transactional row locks (e.g. InnoDB for MySQL).

## License

Proprietary — AIROD Internal Use Only.
