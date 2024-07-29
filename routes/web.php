<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;

        return view('dashboard', compact('year', 'month', 'daysInMonth', 'startOfMonth'));

        // $vehicles = Vehicle::with('reservations')->get();
        // $dates = collect();

        // for ($i = 0; $i < 7; $i++) {
        //     $dates->push(Carbon::today()->addDays($i));
        // }

        // return view('dashboard', compact('vehicles', 'dates'));
        // // return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::resource('vehicles', VehicleController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('requests', RequestController::class);
});
