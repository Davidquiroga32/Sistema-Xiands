<?php

use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\ConsignacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard (requires auth)
Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::redirect('/dashboard', '/');

// Personas
Route::resource('personas', PersonaController::class)
    ->middleware('auth');

Route::post('personas/{persona}/deactivate', [PersonaController::class, 'deactivate'])
    ->middleware(['auth', 'role:administradora'])
    ->name('personas.deactivate');

Route::post('personas/{persona}/restore', [PersonaController::class, 'restore'])
    ->middleware(['auth', 'role:administradora'])
    ->name('personas.restore');

Route::post('personas/{persona}/interes-batch', [PersonaController::class, 'aplicarInteresBatch'])
    ->middleware(['auth', 'role:administradora'])
    ->name('personas.interes-batch');

Route::post('personas/{persona}/cambiar-estado', [PersonaController::class, 'cambiarEstadoBatch'])
    ->middleware('auth')
    ->name('personas.cambiar-estado');

// Consignaciones
Route::resource('consignaciones', ConsignacionController::class)
    ->parameters(['consignaciones' => 'consignacion'])
    ->middleware('auth');

// Aplicar interés (solo administradora)
Route::post('consignaciones/{consignacion}/interes', [ConsignacionController::class, 'aplicarInteres'])
    ->middleware(['auth', 'role:administradora'])
    ->name('consignaciones.interes');

// Cambiar estado de consignacion
Route::post('consignaciones/{consignacion}/toggle-estado', [ConsignacionController::class, 'toggleEstado'])
    ->middleware('auth')
    ->name('consignaciones.toggle-estado');

// Reportes (solo Administradora)
Route::middleware(['auth', 'role:administradora'])->group(function () {
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/pdf', [ReporteController::class, 'exportPdf'])->name('reportes.pdf');
    Route::get('reportes/excel', [ReporteController::class, 'exportExcel'])->name('reportes.excel');
});

// Comprobantes
Route::get('comprobantes/{consignacion}', [ComprobanteController::class, 'show'])
    ->middleware('auth')
    ->name('comprobantes.show');

Route::post('ocr/procesar', [ComprobanteController::class, 'procesar'])
    ->middleware('auth')
    ->name('ocr.procesar');

// Perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Gestión de usuarios (solo administradora)
Route::middleware(['auth', 'role:administradora'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::patch('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
