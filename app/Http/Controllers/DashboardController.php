<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Ottieni anno e mese correnti se non sono forniti
        $currentDate = Carbon::now();
        $year = request('year', $currentDate->year);  // Usa request() per consentire la personalizzazione tramite parametri GET
        $month = request('month', $currentDate->month);

        // Calcola inizio e fine mese
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;

        // Ricavo i veicoli in manutenzione per i prossimi 2 mesi, con cache per migliorare le prestazioni
        $vehiclesInMaintenance = Cache::remember("vehicles_in_maintenance_{$year}_{$month}", 60, function () {
            return Maintenance::with('vehicle')
                ->active()
                ->where(function($query) {
                    $query->where('start_date', '<=', Carbon::now()->addMonths(2))
                          ->where('end_date', '>=', Carbon::now());
                })
                ->orderBy('start_date')
                ->get()
                ->groupBy('vehicle_id');
        });

        // Calcola statistiche per le card
        $today = Carbon::today();

        // Veicoli totali
        $totalVehicles = \App\Models\Vehicle::count();

        // Veicoli in manutenzione oggi
        $vehiclesInMaintenanceToday = Maintenance::active()
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->distinct('vehicle_id')
            ->count('vehicle_id');

        // Veicoli prenotati oggi (confirmed + pending)
        $vehiclesReservedToday = Reservation::whereDate('date', $today)
            ->whereIn('status', ['confirmed', 'pending'])
            ->distinct('vehicle_id')
            ->count('vehicle_id');

        // Veicoli liberi oggi
        $vehiclesAvailableToday = $totalVehicles - $vehiclesInMaintenanceToday - $vehiclesReservedToday;

        // Prenotazioni totali oggi (confirmed + pending)
        $reservationsToday = Reservation::whereDate('date', $today)
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();

        // Prenotazioni in attesa (solo future o di oggi)
        // Raggruppiamo le prenotazioni consecutive per contare i gruppi effettivi
        $pendingReservations = Reservation::where('status', 'pending')
            ->where('date', '>=', $today)
            ->orderBy('driver_id', 'asc')
            ->orderBy('vehicle_id', 'asc')
            ->orderBy('date', 'asc')
            ->get();

        $reservationsPending = 0;
        $currentGroup = null;

        foreach ($pendingReservations as $reservation) {
            $reservationDate = Carbon::parse($reservation->date);

            if ($currentGroup === null) {
                // Inizia un nuovo gruppo
                $currentGroup = [
                    'driver_id' => $reservation->driver_id,
                    'vehicle_id' => $reservation->vehicle_id,
                    'last_date' => $reservationDate,
                ];
                $reservationsPending++;
            } else {
                $sameDriver = $currentGroup['driver_id'] === $reservation->driver_id;
                $sameVehicle = $currentGroup['vehicle_id'] === $reservation->vehicle_id;
                $isConsecutive = $currentGroup['last_date']->copy()->addDay()->isSameDay($reservationDate);

                if ($sameDriver && $sameVehicle && $isConsecutive) {
                    // Aggiungi al gruppo corrente
                    $currentGroup['last_date'] = $reservationDate;
                } else {
                    // Inizia un nuovo gruppo
                    $currentGroup = [
                        'driver_id' => $reservation->driver_id,
                        'vehicle_id' => $reservation->vehicle_id,
                        'last_date' => $reservationDate,
                    ];
                    $reservationsPending++;
                }
            }
        }

        // Passa i dati alla vista
        return view('dashboard', compact(
            'year',
            'month',
            'daysInMonth',
            'startOfMonth',
            'vehiclesInMaintenance',
            'totalVehicles',
            'vehiclesAvailableToday',
            'vehiclesReservedToday',
            'vehiclesInMaintenanceToday',
            'reservationsToday',
            'reservationsPending'
        ));
    }
}
