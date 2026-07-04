# AGENTS.md — XIANDS

> Mobile-first Laravel app for managing consignaciones (consignments) to registered persons.
> Stack: Laravel 13 · Livewire 4 · Blade · Tailwind CSS 3 · Alpine.js · Spatie Permission · Laravel Auditing

---

## Quick-start commands

```bash
# First-time setup (install deps, create .env, key, migrate, build)
composer setup

# Dev server (runs artisan serve + queue + logs + vite concurrently)
composer dev

# Run all tests (uses SQLite :memory: — no MySQL needed)
composer test
# Single test
php artisan test --filter=ExampleTest

# Lint PHP (Laravel Pint — no custom pint.json, uses Laravel default preset)
./vendor/bin/pint

# Frontend
npm run dev        # Vite dev server with HMR
npm run build      # Production build

# Migrate + seed (RoleSeeder, AdminSeeder, TestDataSeeder)
php artisan migrate:fresh --seed
```

---

## Architecture

- Standard Laravel structure — controllers talk directly to Eloquent models. Only one service class: `app/Services/OcrService.php`.
- **Frontend is Blade + Livewire**, not a JS SPA. There is one Livewire SFC: `resources/views/components/buscar-persona.blade.php` (anonymous class). Everything else in `components/` is a plain Blade component.
- Three main resource areas: **Personas** (`PersonaController`), **Consignaciones** (`ConsignacionController`), **Reportes** (`ReporteController`, admin-only).
- Auth routes (Breeze + Google OAuth via Socialite) are in `routes/auth.php`.

### Routing (`routes/web.php`)

- `Route::resource('consignaciones', ...)->parameters(['consignaciones' => 'consignacion'])` — the bound parameter is `$consignacion` (singular), not `$consignaciones`.
- `POST consignaciones/{consignacion}/interes` — apply 5% interest. Restricted to `role:administradora`.
- All `reportes/*` routes are behind `role:administradora` middleware.
- `GET comprobantes/{consignacion}` redirects to a temporary signed URL; does not serve the file directly.
- `POST ocr/procesar` — runs OCR on an uploaded comprobante image to prefill the consignacion form. Handled by `ComprobanteController`, which also serves comprobante signed URLs.
- `POST personas/{persona}/deactivate` — soft-deactivate a persona (admin only).
- `POST personas/{persona}/restore` — restore a soft-deleted persona (admin only).
- `POST personas/{persona}/interes-batch` — batch apply 5% interest to all consignaciones of a persona (admin only).

### Data model

- All models use **ULID** primary keys (`HasUlids`). Foreign keys use `foreignUlid()`.
- `Persona` and `Consignacion` use `SoftDeletes` and implement `Auditable` (Owen-IT) — writes to `audits` table, visible in Reportes audit log.
- `User` does **not** use `SoftDeletes`.
- `Consignacion belongsTo Persona`. Interest fields (`interes_aplicado`, `total_con_interes`, `interes_aplicado_by`, `interes_aplicado_at`) are only set by `aplicarInteres()` with a hardcoded 5% rate — interest cannot be edited from the update form.
- Roles: `administradora` (full access — delete, reports, apply interest) and `secretaria` (everything else). Seeded by `RoleSeeder`; default admin: `admin@xiands.com` / `password` (via `AdminSeeder`).

### File storage (comprobantes)

- Uploaded comprobantes go to the disk in `config('filesystems.comprobantes_disk')` (env `FILESYSTEM_COMPROBANTES`). Default is `'public'` (local `storage/app/public`).
- `ConsignacionController::disk()` has its own fallback default of `'b2'` via `config('filesystems.comprobantes_disk', 'b2')` — but since the config key always exists (defaults to `'public'`), this fallback is never reached unless you change the config. The method also falls back from `b2`→`public` (if no B2 key configured) and `local`→`public`. Do **not** assume B2 is always available.
- Comprobante view URLs are always temporary signed URLs (`Storage::disk(...)->temporaryUrl(...)`), generated on-read, never persisted.

### OCR (`app/Services/OcrService.php`)

- Dispatches on `config('services.ocr.engine')`: `tesseract` (needs Tesseract binary installed on host + `thiagoalessio/tesseract-ocr` Composer package installed manually — it is **not** in `composer.json`) or `google_vision` (stub, always returns `null`). Default is `none` — OCR does nothing unless explicitly configured.
- Regex-based parsing extracts date, monetary value, Colombian bank names, and reference numbers from raw OCR text.

### Policies

- `PersonaPolicy` and `ConsignacionPolicy` exist. `viewAny`/`view`/`create`/`update` return `true` for any authenticated user. `delete`/`restore`/`forceDelete` require `administradora` role.
- Policies are only explicitly enforced in `destroy()` methods via `$this->authorize('delete', ...)`. Other actions rely on route-level `role:` middleware.

### Export (Reportes)

- PDF via `barryvdh/laravel-dompdf`; Excel via `maatwebsite/excel`. Both accept `tipo` (`consignaciones`|`personas`) and optional `fecha_desde`/`fecha_hasta` filters (applied only to consignaciones export).

---

## Styling conventions

- **Custom dark theme** — most styles use CSS classes defined in `resources/css/app.css` (`@layer components`): `.btn-primary-dark`, `.card-dark`, `.input-dark`, `.label-dark`, etc., plus CSS variables (`--black`, `--card`, `--border`, `--silver*`, `--accent`) in `:root`. Do not rely on utility-first Tailwind for component styling.
- Every form input needs a visible `<label>` above it — placeholder is never a label substitute.
- Interactive buttons must be at least `48px` tall (touch target) — `.btn` base class enforces `min-height: 48px`.
- Bottom nav bar (`x-bottom-nav`) is always visible on mobile. No desktop sidebar yet.
- Blade component library for this project: `toast`, `bottom-sheet`, `skeleton`, `money-input`, `modal` (in `resources/views/components/`).

---

## Testing

- `phpunit.xml` forces SQLite `:memory:`, array cache/session, sync queue. Tests never touch MySQL config.
- `composer test` clears cached config first (`config:clear`) — always use this over bare `php artisan test` if config may be stale.
- Only Breeze auth/profile tests exist (`tests/Feature/Auth/`, `tests/Feature/ProfileTest.php`). No feature tests for Personas, Consignaciones, or Reportes yet.

---

## Environment notes

- `.env.example` defaults to `DB_CONNECTION=sqlite`. Local development `.env` typically uses MySQL (`xiands` / `root` / empty password).
- Session, cache, and queue drivers use `database` in normal operation. The queue worker runs via `composer dev` (`queue:listen`). If running `artisan serve` manually, start the queue worker separately.
- App locale is Spanish (`es`), Faker locale is `es_CO`.

---

## Related files

- `CLAUDE.md` — companion doc with additional detail on OcrService parsing, export classes, and view structure. Useful reference.
