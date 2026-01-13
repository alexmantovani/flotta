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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reservation/validate', [ReservationController::class, 'validate'])->name('reservation.validate');

});

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::post('vehicle/import', [VehicleController::class, 'import'])->name('vehicle.import');
    Route::resource('vehicle', VehicleController::class);
    Route::resource('driver', DriverController::class);
    Route::resource('reservation', ReservationController::class);
    Route::resource('request', RequestController::class);
    Route::resource('maintenance', MaintenanceController::class);
});
