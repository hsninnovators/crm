# White-Label CRM (Core PHP + MySQL)

A Worksuite-style CRM built with **Core PHP (no framework)**, **PDO**, **MySQL**, **Bootstrap**, and AJAX polling. It is designed for **shared hosting/cPanel** and includes a web installer plus white-label branding.

## Features Included
- Dashboard with stats and finance chart.
- CRM modules: customers, leads, projects, tasks, tickets, invoices, estimates, contracts, events, leaves, attendance, products, notices, reports.
- Leads conversion to customers.
- Task status and progress, plus starter Kanban/Gantt-ready UI classes.
- Messaging (private/group) and admin visibility for all chats.
- Notifications stored in DB.
- Finance records (manual + automatic from invoice inserts).
- User management base, roles/permissions schema.
- Global search, pagination/filter on resource list page.
- Activity logs.
- File storage folders for docs/branding/signatures.
- White-label dynamic branding from DB and `/uploads/branding/`.
- Security: `password_hash()`, prepared statements, CSRF token checks, session auth + timeout.
- Clean URLs via `.htaccess` rewrite.

## Requirements
- PHP **8.0+** (PDO, PDO_MySQL enabled)
- MySQL / MariaDB
- Apache with `mod_rewrite`
- Shared hosting or cPanel compatible environment

## Installation (Web Installer)
1. Upload files to your hosting root (e.g., `public_html/crm`).
2. Create a MySQL database and user in cPanel.
3. Ensure writable folders:
   - `uploads/`
   - `uploads/branding/`
   - `uploads/files/`
   - `uploads/contracts/`
4. Open `https://your-domain.com/installer.php`.
5. Fill in database credentials and create admin account.
6. Installer imports `install.sql`, creates admin user, and writes `config/config.php`.
7. Login via `https://your-domain.com/login`.

## Default Login
- There is **no hardcoded default** user/password.
- You create the admin account during installation.

## Folder Structure
```
/app
  /controllers
  /models
  /views
/config
/public
/uploads
/assets
index.php
installer.php
install.sql
.htaccess
```

## White-Label Setup
Go to **Settings → Brand Settings** and configure:
- Company name
- Header/footer text
- Logo, favicon
- Login background
- Email brand name/logo

All values are stored in `settings` and files in `/uploads/branding/`.

## Backup System (DB Download)
- Implement as a cPanel cron or phpMyAdmin export.
- Recommended command for shell-enabled hosting:
  - `mysqldump -uUSER -p DBNAME > backup.sql`

## Troubleshooting
- **Blank page**: enable `display_errors` temporarily and check PHP error logs.
- **DB connection error**: verify `config/config.php` credentials.
- **404 on routes**: ensure Apache `mod_rewrite` and `.htaccess` allowed.
- **Upload not working**: verify folder permissions and PHP upload limits.
- **Session logout too soon**: adjust `session_timeout` in `config/config.php`.

## Notes
- PDF generation/export and signature drawing are prepared at schema/file-storage level and can be integrated with your preferred free library (e.g., dompdf/signature pad) without framework dependency.
- No paid APIs or payment gateway dependencies are required.
