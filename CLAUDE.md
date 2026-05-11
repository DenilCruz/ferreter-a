# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

# Development
php artisan serve           # localhost:8000
php artisan migrate:fresh --seed

# Testing
php artisan config:clear && php artisan test
php artisan test --filter=TestClassName   # single test

# Docker
docker-compose up           # App on :8080, MySQL on :3307
```

Default admin credentials (dev/seed only): `admin@ferre.bo` / `admin123`

## Architecture

Laravel 11 MVC application for inventory and personnel management of a hardware store (ferretería).

### Dual User Model

There are **two separate user models** — this is the most important architectural detail:
- `User` — Laravel's standard auth model (used by Breeze/sessions)
- `Usuario` — Custom business model (`usuario` table) with role assignments and employee linkage

The `AdminMiddleware` checks `Usuario` roles, not `User`. Routes under `/usuarios`, `/trabajos/rol`, `/trabajos/asignar`, and `/bitacora` require admin role.

### Route Access Levels

- **Public** (`/`) — Product listing, no auth
- **Authenticated** — `/dashboard`, `/profile`, `/productos` CRUD, `/trabajos`
- **Admin-only** — `/usuarios` CRUD, role/assignment management, `/bitacora` audit log

### Audit Logging

`Bitacora` model auto-records INSERT/UPDATE/DELETE events across the app. When modifying data-mutating controllers, ensure audit entries are written consistently with the existing pattern in other controllers.

### Database Schema Notes

Business tables use Spanish names (`producto`, `categoria`, `marca`, `empleado`, `rol`, `estado_rol`). The `usuario` table was migrated to add auth fields and rename `correo` → `email` — check migration history before adding columns to this table.

### Frontend

Blade templates with a Glassmorphism/premium UI design. CSS animations and glass-effect styles are shared across `layouts/`. Avoid introducing plain Bootstrap or Tailwind utilities that conflict with the existing design system.

### Testing

PHPUnit uses SQLite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`) defined in `phpunit.xml`. Tests do not hit MySQL.
