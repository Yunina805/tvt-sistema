# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**TVT Sistema** is a management system for a cable TV + internet ISP ("Tu Visión Telecable"). It handles client service lifecycle: new contracts, monthly payments, service reports/faults, suspensions, reconnections, cancellations, and equipment recovery.

## Tech Stack

- **Backend**: PHP 8.2+, Laravel 12
- **Reactive UI**: Livewire 3 (full-page components) + Livewire Volt (inline components for layout)
- **Frontend**: Tailwind CSS v3, Alpine.js, Remix Icons
- **Build**: Vite with `laravel-vite-plugin`
- **Dev environment**: Laragon (local server at `C:\laragon\www\tvt-sistema`)
- **DB**: SQLite (testing), MySQL (production via Laragon)

## Common Commands

```bash
# Full dev environment (server + queue + logs + Vite HMR)
composer dev

# Build frontend assets
npm run dev       # watch mode
npm run build     # production

# Run all tests
composer test
php artisan test

# Run a single test file
php artisan test tests/Feature/ExampleTest.php

# Run a specific test method
php artisan test --filter=test_method_name

# Code style (Laravel Pint)
./vendor/bin/pint

# Database
php artisan migrate
php artisan db:seed

# Tinker REPL
php artisan tinker
```

## Architecture

### Livewire Component Pattern

Every full-page view has a paired Livewire component and Blade template:

- `app/Livewire/GestionClientes/ComponentName.php` — business logic
- `resources/views/livewire/gestion-clientes/component-name.blade.php` — template

Full-page components declare the layout with the PHP attribute `#[Layout('layouts.app')]`.

Layout-embedded components (sidebar navigation, notifications) live at:
- `app/Livewire/NotificacionesTopBar.php`
- `resources/views/livewire/layout/navigation.blade.php` (Volt inline component)

### App Layout (`resources/views/layouts/app.blade.php`)

The main shell uses Alpine.js for sidebar collapse (`sidebarOpen`) and mobile overlay (`mobileSidebarOpen`). The sidebar has collapsible sections with `.hide-collapsed` / `.sidebar-collapsed` CSS classes. Active nav links use the `.nav-active` CSS class.

### GestionClientes Module

Currently the only implemented module. All routes require `auth` + `verified` middleware. Route groups in `routes/web.php`:

| Route | Component | Purpose |
|---|---|---|
| `/contrataciones-nuevas` | `ContratacionNueva` | New service contracts |
| `/servicios-adicionales` | `ServiciosAdicionales` | Add-on services |
| `/contratacion-promocion` | `ContratacionPromocion` | Promotional contracts |
| `/pago-mensualidad` | `PagoMensualidad` | Monthly billing |
| `/estado-cuenta` | `EstadoCuenta` | Account statements |
| `/suspension-falta-pago` | `SuspensionClientes` | Suspension workflow |
| `/reconexion-cliente` | `ReconexionCliente` | Reconnection workflow |
| `/cancelacion-servicio` | `CancelacionServicio` | Service cancellation |
| `/recuperacion-equipos` | `RecuperacionEquipos` | Equipment recovery |
| `/reportes-servicio` | `ReportesServicio` | Service reports list (KPI + filters) |
| `/reportes-servicio/atender/{folio?}` | `AtenderReporte` | Individual report detail/closure |

### AtenderReporte — Report Type System

`AtenderReporte` is the most complex component. Its behavior is entirely driven by `$reporte['tipo_reporte']`:

- `INSTALACION`, `FALLA_TV`, `FALLA_INTERNET`, `FALLA_TV_INTERNET`, `CAMBIO_DOMICILIO` → renders `cerrarReporte()` with optical power readings + technician checklist
- `SUSPENSION` → renders `cerrarSuspension()`
- `CANCELACION` → renders `cerrarCancelacion()`
- `RECUPERACION` → renders `cerrarRecuperacion()`

The service type (`$reporte['tipo_servicio']`: `TV`, `INTERNET`, `TV+INTERNET`) controls which technical checklist sections appear.

### Mock Data / TODO Pattern

**All components currently use in-memory mock data** — the application has no domain models yet (only the default `users` table migration). Every component's `render()` and action methods contain `// TODO:` comments with the exact Eloquent queries and DB transactions to implement. When adding real DB logic, follow the commented-out Eloquent patterns already present in each component.

### Frontend Conventions

- Icons: Remix Icons (`ri-*` classes) loaded from CDN in `layouts/app.blade.php`
- Typography: Inter font (Bunny Fonts CDN), utility text sizes `text-[10px]`, `text-xs`, `text-sm`
- KPI cards follow the pattern: `bg-*-50` background, `bg-*-100` icon background, `text-*-600` icon color
- Flash messages use `session()->flash('exito', ...)` and `session()->flash('info', ...)`
- Breadcrumbs appear inside each blade template, not in the layout

### Planned Modules (sidebar stubs, not yet implemented)

Planta Externa (NAPs, OLTs, ONUs, fiber), Televisión (mininodos, channels), Internet (VLANs, Winbox, CCR), Finanzas (caja, ingresos, cortes mensuales), Catálogos, Recursos Humanos.
