# AGENTS.md — XIANDS

> Mobile-first Laravel app for managing consignaciones (consignments) to registered persons.
> Stack: Laravel 13 · Livewire 4 · Blade · Tailwind CSS 3 · Alpine.js · Spatie Permission

---

## Quick-start commands

```bash
# First-time setup (install deps, create .env, key, migrate, build)
composer setup

# Dev server (runs server + queue + logs + vite concurrently)
composer dev

# Run all tests (uses SQLite :memory: — no MySQL needed)
php artisan test
# Single test file
php artisan test --filter=ExampleTest

# Lint PHP
./vendor/bin/pint
# or: php artisan pint (if registered)

# Frontend
npm run dev        # Vite dev server with HMR
npm run build      # Production build

# Migrate + seed (creates roles: administradora, secretaria + admin@xiands.com / password)
php artisan migrate:fresh --seed

# Make a Livewire component (SFC with ⚡ emoji prefix by default)
php artisan make:livewire NombreComponente
```

## Architecture

- **App code** is in `xiands-app/`. All commands run from that directory.
- **Root level** contains prototyping HTML files (`01-login.html`, etc.) and `agent.md` (the original spec/requirements doc — useful reference but its version info is outdated; actual stack is newer).
- **Frontend is Blade + Livewire SFC**, not a JavaScript SPA. Livewire 4 SFC components live at `resources/views/components/⚡*.blade.php` (emoji prefix convention).
- **Livewire class namespace**: `App\Livewire`
- **Livewire component locations**: `resources/views/components` and `resources/views/livewire`

## Key conventions

### Styling
- **Custom dark theme** — not standard Tailwind utility-first. Most styling uses custom CSS classes (`.btn-primary-dark`, `.card-dark`, `.input-dark`, `.label-dark`, etc.) and CSS variables (`--silver`, `--border`, `--card`, etc.) defined in `resources/css/app.css`.
- Every form input needs a `<label>` above it — never use placeholder as label substitute (mobile-first rule).
- Buttons must be at least `48px` tall (touch target).
- Bottom nav bar is always visible on mobile (`x-bottom-nav` component). Desktop would use sidebar but that's not implemented yet.

### Routing
- Route model binding for `consignaciones` uses explicit parameter mapping: `Route::resource('consignaciones', ...)->parameters(['consignaciones' => 'consignacion'])`. So in controllers, the parameter is `$consignacion`, not `$consignaciones`.
- Auth routes are in `routes/auth.php` (Laravel Breeze).

### Database
- All models use **ULID** primary keys (`HasUlids` trait), not auto-increment. Foreign keys use `foreignUlid()`.
- All models use `SoftDeletes`.
- Roles: `administradora` and `secretaria`. Created by `RoleSeeder`.
- Default admin: `admin@xiands.com` / `password` (via `AdminSeeder`).

### Permissions
- Only `administradora` role can apply interest (`POST consignaciones/{consignacion}/interes`) — enforced via `middleware('role:administradora')`.
- Only `administradora` should be able to delete records and access reports (not yet enforced via policies for delete — this is a TODO).

## What's built vs. not built

### Built
- DB schema: personas, consignaciones, users, permission tables
- Models: Persona, Consignacion, User (with Spatie HasRoles)
- CRUD controllers: PersonaController, ConsignacionController
- DashboardController with KPIs
- Form Requests with validation
- Views: Dashboard, Personas (index/show/create/edit), Consignaciones (index/create/show — **edit view is missing**)
- Livewire component: `⚡buscar-persona` (person search with debounce, inline rendering in personas index)
- Layouts: `app.blade.php` (mobile-first with top bar + bottom nav + FAB), `auth.blade.php`, `guest.blade.php`
- Profile edit (Breeze)
- Seeders: RoleSeeder, AdminSeeder

### Not yet built (from spec)
- Consignaciones `edit.blade.php` view
- Reportes module (controller, views, bottom nav "Reportes" tab is a dead `#` link)
- PDF export (barryvdh/laravel-dompdf — not installed)
- Excel export (Maatwebsite/Laravel-Excel — not installed)
- OCR service for comprobantes
- ComprobanteController (signed URLs for viewing)
- Policies (PersonaPolicy, ConsignacionPolicy)
- Audit trail (OwenIt/Laravel-Auditing — not installed)
- Cloudflare R2 integration (currently using local storage driver)
- Toast/notification component
- Bottom sheet component
- Skeleton loader component

## Test conventions
- Tests use SQLite `:memory:` (configured in `phpunit.xml`). Migrations run automatically via `RefreshDatabase` trait.
- Test namespace: `Tests\` (PSR-4 from `tests/`)
- `composer test` clears config cache first to avoid stale cached config breaking SQLite tests.

## Environment notes
- `.env` is set for MySQL (`DB_DATABASE=xiands`, `DB_USERNAME=root`, empty password). Change as needed.
- Session, cache, and queue drivers all use the `database` driver — tables are auto-created by migrations.
- Default locale: Spanish (`es`), faker locale: `es_CO`.
- Queue worker is started by `composer dev` (runs `queue:listen`). If running artisan serve manually, start the queue separately if using async features.
