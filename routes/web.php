<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ConsignacionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Dashboard (requires auth)
Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dashboard', fn() => redirect()->route('dashboard'));

// Personas
Route::resource('personas', PersonaController::class)
    ->middleware('auth');

// Consignaciones
Route::resource('consignaciones', ConsignacionController::class)
    ->parameters(['consignaciones' => 'consignacion'])
    ->middleware('auth');

// Aplicar interés (solo administradora)
Route::post('consignaciones/{consignacion}/interes', [ConsignacionController::class, 'aplicarInteres'])
    ->middleware(['auth', 'role:administradora'])
    ->name('consignaciones.interes');

// Perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
