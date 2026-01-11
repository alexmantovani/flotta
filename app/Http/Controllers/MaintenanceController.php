<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenances
     */
    public function index(Request $request)
    {
        $query = Maintenance::with('vehicle')
            ->orderBy('start_date', 'desc');

        // Filtro per status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtro per veicolo
        if ($request->has('vehicle_id') && $request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filtro per tipo
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filtro per periodo
        if ($request->has('period')) {
            switch ($request->period) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'in_progress':
                    $query->inProgress();
                    break;
                case 'completed':
                    $query->completed();
                    break;
            }
        }

        $maintenances = $query->paginate(20);
        $vehicles = Vehicle::orderBy('plate')->get();

        return view('maintenance.index', compact('maintenances', 'vehicles'));
    }

    /**
     * Show the form for creating a new maintenance
     */
    public function create(Request $request)
    {
        $vehicles = Vehicle::where('status', 'available')->orderBy('plate')->get();

        // Se viene passato un vehicle_id, preselezionalo
        $selectedVehicleId = $request->input('vehicle_id');

        return view('maintenance.create', compact('vehicles', 'selectedVehicleId'));
    }

    /**
     * Store a newly created maintenance in storage
     */
    public function store(StoreMaintenanceRequest $request)
    {
        $validated = $request->validated();

        // Verifica sovrapposizioni con altre manutenzioni
        $overlapping = Maintenance::where('vehicle_id', $validated['vehicle_id'])
            ->active()
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                          ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dates' => 'Esiste già una manutenzione programmata per questo veicolo nel periodo selezionato.']);
        }

        // Verifica sovrapposizioni con prenotazioni
        $hasReservations = Reservation::where('vehicle_id', $validated['vehicle_id'])
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->where('status', '!=', 'maintenance')
            ->exists();

        if ($hasReservations) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dates' => 'Il veicolo ha prenotazioni attive nel periodo selezionato. Eliminare o spostare le prenotazioni prima di programmare la manutenzione.']);
        }

        $maintenance = Maintenance::create($validated);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Manutenzione programmata con successo.');
    }

    /**
     * Display the specified maintenance
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->load('vehicle');
        return view('maintenance.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified maintenance
     */
    public function edit(Maintenance $maintenance)
    {
        $vehicles = Vehicle::orderBy('plate')->get();
        return view('maintenance.edit', compact('maintenance', 'vehicles'));
    }

    /**
     * Update the specified maintenance in storage
     */
    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance)
    {
        $validated = $request->validated();

        // Verifica sovrapposizioni (escludendo questa manutenzione)
        $overlapping = Maintenance::where('vehicle_id', $validated['vehicle_id'])
            ->where('id', '!=', $maintenance->id)
            ->active()
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                          ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['dates' => 'Esiste già una manutenzione programmata per questo veicolo nel periodo selezionato.']);
        }

        $maintenance->update($validated);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Manutenzione aggiornata con successo.');
    }

    /**
     * Remove the specified maintenance from storage
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Manutenzione eliminata con successo.');
    }

    /**
     * Check availability for a vehicle in a date range
     * API endpoint
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exclude_maintenance_id' => 'nullable|exists:maintenances,id'
        ]);

        $conflicts = [];

        // Verifica manutenzioni
        $maintenanceQuery = Maintenance::where('vehicle_id', $request->vehicle_id)
            ->active()
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            });

        if ($request->has('exclude_maintenance_id')) {
            $maintenanceQuery->where('id', '!=', $request->exclude_maintenance_id);
        }

        $conflictingMaintenances = $maintenanceQuery->get();

        if ($conflictingMaintenances->isNotEmpty()) {
            foreach ($conflictingMaintenances as $m) {
                $conflicts[] = [
                    'type' => 'maintenance',
                    'start_date' => $m->start_date->format('Y-m-d'),
                    'end_date' => $m->end_date->format('Y-m-d'),
                    'reason' => $m->reason
                ];
            }
        }

        // Verifica prenotazioni
        $reservations = Reservation::where('vehicle_id', $request->vehicle_id)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->with('driver')
            ->get()
            ->groupBy('date');

        if ($reservations->isNotEmpty()) {
            foreach ($reservations as $date => $dayReservations) {
                $conflicts[] = [
                    'type' => 'reservation',
                    'date' => $date,
                    'driver' => $dayReservations->first()->driver?->name ?? 'N/A'
                ];
            }
        }

        return response()->json([
            'available' => empty($conflicts),
            'conflicts' => $conflicts
        ]);
    }

    /**
     * Get maintenance timeline data for dashboard
     * API endpoint
     */
    public function getTimelineData(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::today()));
        $endDate = Carbon::parse($request->input('end_date', Carbon::today()->addDays(13)));

        $maintenances = Maintenance::with('vehicle')
            ->active()
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->get()
            ->map(function($maintenance) use ($startDate, $endDate) {
                return [
                    'id' => $maintenance->id,
                    'vehicle_id' => $maintenance->vehicle_id,
                    'vehicle_plate' => $maintenance->vehicle->plate,
                    'start_date' => max($maintenance->start_date->format('Y-m-d'), $startDate->format('Y-m-d')),
                    'end_date' => min($maintenance->end_date->format('Y-m-d'), $endDate->format('Y-m-d')),
                    'actual_start_date' => $maintenance->start_date->format('Y-m-d'),
                    'actual_end_date' => $maintenance->end_date->format('Y-m-d'),
                    'reason' => $maintenance->reason ?? 'Manutenzione',
                    'status' => $maintenance->status,
                    'type' => $maintenance->type
                ];
            });

        return response()->json(['maintenances' => $maintenances]);
    }
}
