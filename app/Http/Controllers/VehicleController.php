<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $vehicles = Vehicle::all();
        // return view('vehicles.index', compact('vehicles'));
        $search = $request->input('search');

        $vehiclesQuery = Vehicle::query();

        if ($search) {
            $vehiclesQuery->where('plate', 'like', "%{$search}%")
                ->orWhereHas('reservations.driver', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
        }

        $vehicles = $vehiclesQuery->get();

        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push(Carbon::today()->addDays($i));
        }

        return view('vehicle.index', compact('vehicles', 'dates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $request->validate([
            'plate' => 'required|unique:vehicles',
            'model' => 'required',
            'brand' => 'required',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        // Prenotazioni future (escluse manutenzioni)
        $groupedReservations = $vehicle->reservations()
            ->where('date', '>=', Carbon::today())
            ->where('status', '!=', 'maintenance')
            ->orderBy('date')
            ->get()
            ->groupBy(function ($reservation) {
                return Carbon::parse($reservation->created_at)->format('Y-m');
            });

        // Manutenzioni future
        $upcomingMaintenances = $vehicle->maintenances()
            ->upcoming()
            ->orderBy('start_date')
            ->get();

        // Manutenzioni in corso
        $inProgressMaintenances = $vehicle->maintenances()
            ->inProgress()
            ->get();

        // Storico manutenzioni (ultime 10)
        $pastMaintenances = $vehicle->maintenances()
            ->completed()
            ->limit(10)
            ->get();

        return view('vehicle.show', compact(
            'vehicle',
            'groupedReservations',
            'upcomingMaintenances',
            'inProgressMaintenances',
            'pastMaintenances'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('vehicle.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate' => 'required|unique:vehicles,plate,' . $vehicle->id,
            'model' => 'required',
            'brand' => 'required',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicle.index')->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicle.index')->with('success', 'Vehicle deleted successfully.');
    }
}
