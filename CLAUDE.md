# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### PHP/Laravel Commands
- `composer dev` - Start development environment (serves app, queue, logs, and Vite)
- `composer dev:ssr` - Start development with SSR (builds SSR first, then starts services)
- `composer test` - Run PHP tests (clears config and runs Pest tests)
- `./vendor/bin/pint` - Format PHP code with Laravel Pint
- `php artisan serve` - Start Laravel development server
- `php artisan test` - Run tests using Pest
- `php artisan migrate` - Run database migrations

### Frontend Commands
- `npm run dev` - Start Vite development server
- `npm run build` - Build production assets
- `npm run build:ssr` - Build for server-side rendering
- `npm run lint` - Lint and fix JavaScript/TypeScript/Vue files
- `npm run format` - Format code with Prettier
- `npm run format:check` - Check code formatting

### Testing
- Use Pest PHP for backend testing (configured in `tests/Pest.php`)
- Tests are organized in `tests/Feature/` and `tests/Unit/`
- TypeScript checking: `npx vue-tsc --noEmit`

## Architecture Overview

This is a **Laravel + Vue 3 + Inertia.js + TypeScript** application with authentication and admin features.

### Backend (Laravel)
- **Framework**: Laravel 12 with PHP 8.2+
- **Authentication**: Laravel Fortify with two-factor authentication support
- **Database**: SQLite for development
- **Testing**: Pest PHP framework
- **Code Style**: Laravel Pint for formatting

### Frontend (Vue 3 + TypeScript)
- **Framework**: Vue 3 with Composition API and TypeScript
- **Routing**: Inertia.js for SPA-like experience
- **Styling**: Tailwind CSS v4 with custom components
- **UI Components**: Custom components in `resources/js/components/ui/` (likely shadcn/ui style)
- **Build Tool**: Vite with SSR support
- **Linting**: ESLint with Vue and TypeScript configs

### Key Integrations
- **Laravel Wayfinder**: Type-safe route generation between Laravel and frontend
- **Reka UI**: Vue component library for UI primitives
- **Fortify**: Handles authentication, registration, password reset, and 2FA

### File Structure
- **PHP Controllers**: `app/Http/Controllers/` (Auth + Settings)
- **Vue Pages**: `resources/js/pages/` (organized by feature)
- **Vue Components**: `resources/js/components/`
- **Vue Layouts**: `resources/js/layouts/`
- **Routes**: `routes/web.php`, `routes/auth.php`, `routes/settings.php`
- **TypeScript Actions**: `resources/js/actions/` (generated controller actions)
- **Composables**: `resources/js/composables/` (Vue composition functions)

### Authentication Features
- User registration and login
- Email verification
- Password reset
- Two-factor authentication (TOTP)
- Profile management
- Appearance/theme settings

### Development Workflow
- Use `composer dev` for full-stack development
- Frontend pages are Vue components rendered via Inertia
- Type-safe routing via Laravel Wayfinder
- Real-time logs via Laravel Pail
- Queue processing for background jobs