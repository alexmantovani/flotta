<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ============================================
// ROUTES ACCESSIBILI A TUTTI GLI UTENTI AUTENTICATI
// ============================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard: accessibile a tutti, contenuto condizionale
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Creazione prenotazione: tutti possono creare prenotazioni
    Route::get('/reservation/create', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
});

// ============================================
// ROUTES SOLO PER ADMIN
// ============================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->group(function () {
    // Approvazione prenotazioni
    Route::get('/reservation/validate', [ReservationController::class, 'validate'])->name('reservation.validate');

    // Gestione veicoli
    Route::post('vehicle/import', [VehicleController::class, 'import'])->name('vehicle.import');
    Route::resource('vehicle', VehicleController::class);

    // Gestione conducenti
    Route::post('driver/import', [DriverController::class, 'import'])->name('driver.import');
    Route::resource('driver', DriverController::class);

    // Gestione richieste
    Route::resource('request', RequestController::class);

    // Gestione manutenzioni
    Route::resource('maintenance', MaintenanceController::class);

    // Altre operazioni su prenotazioni (edit, update, delete)
    Route::get('/reservation/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservation/{reservation}', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/reservation/{reservation}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
});
