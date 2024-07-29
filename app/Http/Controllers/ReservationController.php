<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Driver;
use App\Models\Reservation;
use App\Models\Vehicle;
use Carbon\Carbon;
use Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $vehicles = Vehicle::all();
        $drivers = Driver::all()->sortBy('name');
        // $dates = implode(', ', Request()->selected_dates);

        return view('reservations.create', compact('vehicles', 'drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $request->validate([
            'vehicle_id' => 'nullable',
            'driver_id' => 'required',
            'dates' => 'required',
        ]);
        $dates = explode(',', $request->dates);

        // Se non è stata assegnata la macchina ne cerco io una disponibile
        if (! $request->vehicle_id > 0) {
            // $vehicle = Vehicle::getAvailableVehicle($startDate, $endDate);
            $vehicle = Vehicle::getAvailableVehicle($dates);
            if ($vehicle) {
                $request->merge(['vehicle_id' => $vehicle->id]);
            } else {
                return redirect()->route('dashboard')->with('error', 'Non ci sono macchine disponibili per il periodo selezionato.');
            }
        }

        // Creo prenotazioni per ogni giorno nell'intervallo di date
        foreach ($dates as $date) {
            Reservation::create([
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'date' => $date,
                'note' => $request->note,
                'status' => 'confirmed',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Macchina prenotata.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function request()
    {
        // $vehicles = Vehicle::all();
        // $drivers = Driver::all()->sortBy('name');

        return view('reservations.request');
    }

    public function storeRequest(StoreReservationRequest $request)
    {
        $request->validate([
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'date' => 'required|date|after_or_equal:today',
            'duration' => 'required|numeric|min:1',
            'note' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $startDate = Carbon::parse($request->date);
        $endDate = Carbon::parse($request->date)->addDays($request->duration - 1);

        // Se non è stata assegnata la macchina ne cerco io una disponibile
        if ($request->vehicle_id == null) {
            $vehicle = Vehicle::getAvailableVehicle($startDate, $endDate);
            if ($vehicle)
                $request->merge(['vehicle_id' => $vehicle->id]);
        }

        // Creare prenotazioni per ogni giorno nell'intervallo di date
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            Reservation::create([
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'date' => $date,
                'note' => $request->note,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('reservations.create')->with('success', 'Reservation created successfully.');
    }

    public function getReservations()
    {
        $request = Request();
        $date = Carbon::parse($request->input('date'));

        // Ottieni le prenotazioni per la data specificata
        $reservations = Reservation::whereDate('date', $date)->with('driver', 'vehicle')->get();

        $data = $reservations->map(function ($reservation) {
            return [
                'driver' => $reservation->driver ?? "???",
                'vehicle' => $reservation->vehicle ?? "???",
            ];
        });

        return response()->json(['data' => $data]);
    }
}
