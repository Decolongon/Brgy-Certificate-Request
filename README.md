# Barangay Certificate Request System

A web-based application for streamlining barangay certificate requests. Built with Laravel, Vue 3, Inertia, and shadcn-vue.

## Features

- **Role-based access** — Admin and Resident roles with separate dashboards and permissions
- **Certificate request management** — Residents can submit and track barangay certificate requests; admins can manage and process them
- **Responsive UI** — Modern interface built with Tailwind CSS and shadcn-vue components
- **Authentication** — Powered by Laravel Fortify with email verification, two-factor authentication, and passkey support

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 22+
- NPM, Yarn, or Bun
- Database (MySQL, PostgreSQL, SQLite, etc.)

### Clone & Install

```bash
git https://github.com/Decolongon/Brgy-Certificate-Request.git
cd brgy-app

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env, then run migrations
php artisan migrate

# Build frontend assets
npm run build
```

### Development

```bash
# Start the Laravel development server
php artisan serve

# In a separate terminal, run Vite for hot-reload
npm run dev
```

## License

The Laravel + Vue starter kit is open-sourced software licensed under the MIT license.
