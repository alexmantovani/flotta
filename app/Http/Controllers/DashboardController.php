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

        // Passa i dati alla vista
        return view('dashboard', compact('year', 'month', 'daysInMonth', 'startOfMonth', 'vehiclesInMaintenance'));
    }
}
