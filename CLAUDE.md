# CLAUDE.md

Este archivo da contexto a Claude Code (claude.ai/code) para trabajar en este repositorio.

## Descripción del proyecto

App Laravel mobile-first para gestionar *consignaciones* (depósitos) a *personas* registradas. Stack: Laravel 13 · Livewire 4 · Blade · Tailwind CSS 3 · Alpine.js · Spatie Permission · Laravel Auditing. Los textos de la interfaz y los mensajes de validación están en español.

## Comandos

```bash
# Instalación inicial (dependencias, .env, key, migraciones, build)
composer setup

# Servidor de desarrollo (corre php artisan serve + queue:listen + logs de pail + vite juntos)
composer dev

# Correr todos los tests (usa SQLite :memory: — no necesita MySQL)
composer test
# equivale a: php artisan config:clear && php artisan test
php artisan test --filter=ExampleTest      # un solo archivo/método de test

# Lint de PHP (Laravel Pint)
./vendor/bin/pint

# Frontend
npm run dev        # Servidor Vite con HMR
npm run build      # Build de producción

# Base de datos
php artisan migrate:fresh --seed   # corre RoleSeeder, AdminSeeder, TestDataSeeder
```

## Arquitectura

- Estructura estándar de Laravel — no hay capa de repositorios/servicios más allá de `app/Services/OcrService.php`. Los controladores hablan directamente con los modelos Eloquent.
- El frontend es Blade + Livewire, no un SPA en JS. Solo hay un componente Livewire real de un solo archivo: `resources/views/components/buscar-persona.blade.php` (clase anónima con `new class extends Component`, bloque PHP arriba del markup Blade). Todo lo demás dentro de `resources/views/components/` es un componente Blade normal.
- Dos recursos principales manejan la app: **Personas** (`PersonaController`, `resources/views/personas/`) y **Consignaciones** (`ConsignacionController`, `resources/views/consignaciones/`), más **Reportes** (`ReporteController`, solo admin) para KPIs, exportación PDF/Excel, y un visor de auditoría.

### Rutas (`routes/web.php`)
- `Route::resource('consignaciones', ...)->parameters(['consignaciones' => 'consignacion'])` — el parámetro del route model binding es `$consignacion`, en singular, no `$consignaciones`.
- `POST consignaciones/{consignacion}/interes` (aplicar 5% de interés) está restringido a `role:administradora`.
- `reportes/*` (index, exportar PDF, exportar Excel) está completamente protegido por `role:administradora`.
- `comprobantes/{consignacion}` redirige a una URL firmada temporal del archivo del comprobante (no sirve el archivo directamente).
- `POST ocr/procesar` corre OCR sobre una imagen de comprobante subida para precargar el formulario de consignación.
- Las rutas de autenticación (Breeze) están en `routes/auth.php`.

### Modelo de datos
- Todos los modelos usan llaves primarias **ULID** (`HasUlids`), no autoincremento. Las llaves foráneas usan `foreignUlid()` en las migraciones.
- `Persona` y `Consignacion` usan `SoftDeletes` e implementan `Auditable` de Owen-IT (escriben en la tabla `audits`, visible en el log de auditoría de Reportes).
- `Consignacion belongsTo Persona`; los campos de interés (`interes_aplicado`, `total_con_interes`, `interes_aplicado_by`, `interes_aplicado_at`) solo se setean desde `aplicarInteres()`, que tiene hardcodeada una tasa del 5% — el interés no se puede editar desde el formulario normal de actualización.
- Roles (Spatie Permission): `administradora` (acceso total — eliminar, reportes, aplicar interés) y `secretaria` (todo lo demás). Sembrados por `RoleSeeder`; el usuario admin por defecto lo crea `AdminSeeder` como `admin@xiands.com` / `password`.
- `PersonaPolicy` / `ConsignacionPolicy`: `viewAny`/`view`/`create`/`update` retornan `true` para cualquier usuario autenticado; `delete`/`restore`/`forceDelete` requieren el rol `administradora`. Los controladores actualmente solo llaman `$this->authorize('delete', ...)` explícitamente en `destroy()` — las demás acciones dependen del middleware `role:` a nivel de ruta, no de las policies.

### Almacenamiento de archivos (comprobantes)
- Los comprobantes subidos van al disco definido en `config('filesystems.comprobantes_disk')` (env `FILESYSTEM_COMPROBANTES`, disco `b2` = Backblaze B2 vía driver compatible con S3).
- `ConsignacionController::disk()` cae a `local` si el disco es `b2` pero no tiene una key configurada — no asumas que B2 siempre está disponible; revisa este fallback si estás depurando problemas de almacenamiento.
- Las URLs para ver un comprobante siempre son firmadas y temporales (`Storage::disk(...)->temporaryUrl(...)`), generadas al momento de leer, nunca persistidas.

### OCR (`app/Services/OcrService.php`)
- Despacha según `config('services.ocr.engine')`: `tesseract` (vía `thiagoalessio/tesseract-ocr`, requiere el binario de Tesseract instalado en el host) o `google_vision` (stub, no implementado — siempre retorna `null`). El valor por defecto es `none`, es decir, el OCR no hace nada a menos que se configure explícitamente.
- El parseo por regex (`parsearTexto`) extrae fecha, valor monetario, una lista hardcodeada de bancos colombianos, y un número de referencia del texto crudo del OCR — es heurístico, no un parser estructurado.

### Reportes/exportaciones (`ReporteController`, `app/Exports/`)
- La exportación a PDF usa `barryvdh/laravel-dompdf`; la exportación a Excel usa `maatwebsite/excel`. Ambas aceptan `tipo` (`consignaciones`|`personas`) y filtros opcionales `fecha_desde`/`fecha_hasta`, aplicados solo a la exportación de `consignaciones`.

### Estilos
- Tema oscuro personalizado, no utility-first de Tailwind — la mayoría de los estilos viven en `resources/css/app.css` dentro de `@layer components` (`.btn-primary-dark`, `.card-dark`, `.input-dark`, `.label-dark`, etc.) más variables CSS (`--black`, `--card`, `--border`, `--silver*`, `--accent`, etc.) definidas en `:root`.
- Todo input de formulario necesita un `<label>` visible — el placeholder nunca sustituye al label (regla mobile-first).
- Los botones interactivos deben medir al menos `48px` de alto (touch target) — la clase base `.btn` fuerza `min-height: 48px`.
- La barra de navegación inferior (`x-bottom-nav`) siempre está visible en mobile; todavía no existe un sidebar para desktop.

## Testing
- `phpunit.xml` fuerza SQLite `:memory:`, cache/sesión en array, cola sync — los tests nunca tocan la configuración de MySQL del `.env`. `RefreshDatabase` corre las migraciones automáticamente en cada test.
- `composer test` limpia la config cacheada primero (`config:clear`) para evitar que una cache vieja apuntando a MySQL rompa los tests con SQLite — corre los tests con `composer test` en vez de `php artisan test` directo si hay posibilidad de config cacheada.
- Por ahora solo existen los tests de auth/perfil de Breeze (`tests/Feature/Auth/`, `tests/Feature/ProfileTest.php`) — todavía no hay tests de feature para Personas, Consignaciones o Reportes.

## Notas de entorno
- `.env` tiene `DB_CONNECTION` por defecto en MySQL (`xiands` / `root` / sin password) para desarrollo local — no lo usa el test suite.
- Sesión, cache y cola usan el driver `database` en operación normal (fuera de tests); el worker de la cola lo arranca `composer dev` (`queue:listen`) — si corres `artisan serve` manualmente en vez de `composer dev`, arranca `queue:listen` aparte para cualquier cosa que despache jobs.
- El locale por defecto de la app es español (`es`), el locale de Faker es `es_CO`.
