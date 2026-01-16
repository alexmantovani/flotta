<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ============================================
// API ENDPOINTS - PROTETTI CON AUTH
// ============================================
// Questi endpoint sono chiamati dalla dashboard via AJAX
// Usano 'web' middleware per autenticazione basata su sessione

Route::middleware(['web', 'auth'])->group(function () {
    // Timeline data: tutti gli utenti possono vedere
    // ma il controller filtrerà i dati in base al ruolo
    Route::get('/get-reservations', [ReservationController::class, 'getReservations']);
    Route::get('/get-timeline-data', [ReservationController::class, 'getTimelineData']);
    Route::get('/maintenance-timeline-data', [MaintenanceController::class, 'getTimelineData']);

    // Ricerca conducenti: tutti possono cercare per creare prenotazioni
    Route::get('/search-drivers', [ReservationController::class, 'searchDrivers']);

    // SOLO ADMIN: modifica/eliminazione prenotazioni
    Route::middleware(['admin'])->group(function () {
        Route::post('/update-booking-dates', [ReservationController::class, 'updateBookingDates']);
        Route::post('/delete-booking', [ReservationController::class, 'deleteBooking']);
        Route::post('/check-maintenance-availability', [MaintenanceController::class, 'checkAvailability']);
    });
});
