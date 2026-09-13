# Vibe App Laravel Setup

This project was created with Composer from the latest stable `laravel/laravel` skeleton and configured for shared hosting.

## Requirements

- PHP 8.3 or newer
- Composer
- MySQL database credentials from your hosting provider

## Install

```bash
composer create-project laravel/laravel vibe-app
cd vibe-app
composer install
cp .env.example .env
php artisan key:generate
```

## Local Run

```bash
php artisan serve
```

Then open:

```text
http://localhost:8000
```

## Environment

Set these values in `.env`:

```dotenv
APP_NAME="Vibe App"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=your_mysql_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

For production shared hosting, also set:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

## Shared Hosting Notes

The web server document root should point to the `public` folder. On hosts like DreamHost, upload the Laravel project outside the public web root when possible, and point the domain to:

```text
vibe-app/public
```

No Node.js, npm, or Vite build step is required. Tailwind is loaded from the CDN in the Blade view, and local CSS/JS are served from `public/css` and `public/js` through Laravel's `asset()` helper.

## Key Files

```text
vibe-app/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── Controller.php
│           └── HomeController.php
├── public/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   ├── .htaccess
│   └── index.php
├── resources/
│   └── views/
│       └── home.blade.php
├── routes/
│   └── web.php
├── .env
├── .env.example
├── composer.json
└── PROJECT_SETUP.md
```
