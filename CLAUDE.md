# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application with modern frontend tooling using Vite and TailwindCSS. The project uses SQLite as the database and includes the standard Laravel application structure.

## Development Commands

### Laravel Commands
- `php artisan serve` - Start the Laravel development server
- `php artisan test` - Run PHPUnit tests
- `php artisan migrate` - Run database migrations
- `composer dev` - Start full development environment (server, queue, logs, and frontend)

### Frontend Commands
- `npm run dev` - Start Vite development server for frontend assets
- `npm run build` - Build frontend assets for production

### Testing
- `php artisan test` - Run all tests
- `php artisan test --testsuite=Unit` - Run only unit tests
- `php artisan test --testsuite=Feature` - Run only feature tests
- `vendor/bin/phpunit` - Direct PHPUnit execution

### Code Quality
- `vendor/bin/pint` - Format PHP code using Laravel Pint

## Architecture

### Backend Structure
- **MVC Architecture**: Standard Laravel MVC pattern
- **Database**: SQLite with Eloquent ORM
- **Authentication**: Laravel's built-in user authentication system
- **Testing**: PHPUnit with separate Unit and Feature test suites

### Frontend Structure
- **Build Tool**: Vite for asset compilation and hot reload
- **CSS Framework**: TailwindCSS v4 with Vite integration
- **Entry Points**: `resources/css/app.css` and `resources/js/app.js`
- **Views**: Blade templates in `resources/views/`

### Key Directories
- `app/` - Application logic (Models, Controllers, Providers)
- `resources/` - Frontend assets and Blade views
- `routes/` - Route definitions (web.php, console.php)
- `database/` - Migrations, seeders, factories, and SQLite database
- `tests/` - Unit and Feature tests

### Development Environment
The `composer dev` script provides a complete development setup with:
- Laravel development server
- Queue worker
- Laravel Pail for log monitoring
- Vite development server with hot reload

All services run concurrently with colored output for easy identification.