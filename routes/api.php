<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-reservations', [ReservationController::class, 'getReservations']);
Route::get('/get-timeline-data', [ReservationController::class, 'getTimelineData']);
Route::post('/update-booking-dates', [ReservationController::class, 'updateBookingDates']);
Route::post('/delete-booking', [ReservationController::class, 'deleteBooking']);

Route::get('/maintenance-timeline-data', [MaintenanceController::class, 'getTimelineData']);
Route::post('/check-maintenance-availability', [MaintenanceController::class, 'checkAvailability']);
