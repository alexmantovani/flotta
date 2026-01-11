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
        $status = Request()->status ?? 'pending';
        $vehicleId = Request()->vehicle_id ?? null;

        if ($vehicleId) {
            $vehicles = Vehicle::where('id', $vehicleId)->get();
        } else {
            $vehicles = Vehicle::all();
        }

        if ($status == 'maintenance') {
            $drivers = Collect();
        } else {
            $drivers = Driver::all()->sortBy('name');
        }
        // $dates = implode(', ', Request()->selected_dates);

        return view('reservation.create', compact('vehicles', 'drivers', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $request->validate([
            'vehicle_id' => 'nullable',
            'driver_id' => 'nullable',
            'dates' => 'required',
            'status' => 'required',
            'note' => 'nullable',
        ]);
        $dates = array_map('trim', explode(',', $request->dates));
        $dates = array_filter($dates); // Rimuovi eventuali elementi vuoti

        // Se non è stata assegnata la macchina ne cerco io una disponibile
        if (! $request->vehicle_id > 0) {
            $vehicle = Vehicle::getAvailableVehicle($dates);
            if ($vehicle) {
                $request->merge(['vehicle_id' => $vehicle->id]);
            } else {
                return redirect()->route('dashboard')->with('error', 'Non ci sono macchine disponibili per il periodo selezionato.');
            }
        }

        // Verifica che il veicolo non sia in manutenzione (solo per prenotazioni normali)
        if ($request->status !== 'maintenance') {
            $vehicleId = $request->vehicle_id;
            $startDate = min($dates);
            $endDate = max($dates);

            $hasMaintenance = \App\Models\Maintenance::where('vehicle_id', $vehicleId)
                ->active()
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $startDate);
                    });
                })
                ->exists();

            if ($hasMaintenance) {
                return redirect()->route('dashboard')
                    ->with('error', 'Il veicolo è in manutenzione nel periodo selezionato. Scegliere un altro veicolo o periodo.');
            }
        }

        // Verifica conflitti con prenotazioni esistenti
        $vehicleId = $request->vehicle_id;

        // Verifica che ogni data non abbia già una prenotazione
        foreach ($dates as $date) {
            $dateFormatted = Carbon::parse($date)->format('Y-m-d');
            $existingReservation = Reservation::where('vehicle_id', $vehicleId)
                ->whereDate('date', $dateFormatted)
                ->first();

            if ($existingReservation) {
                return redirect()->route('dashboard')
                    ->with('error', 'Il veicolo è già prenotato per il ' . Carbon::parse($date)->format('d/m/Y') . '. Scegliere un altro veicolo o periodo.');
            }
        }

        // Creo prenotazioni per ogni giorno nell'intervallo di date
        foreach ($dates as $date) {
            Reservation::create([
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'date' => $date,
                'note' => $request->note,
                'status' => $request->status,
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
        $reservation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prenotazione eliminata con successo.'
        ]);
    }


    public function validate()
    {
        $pendingReservations = Reservation::where('status', 'pending')
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get();

        $vehicles = Vehicle::all();

        return view('reservation.validate', compact('pendingReservations', 'vehicles'));
    }

    public function request()
    {
        // $vehicles = Vehicle::all();
        // $drivers = Driver::all()->sortBy('name');

        return view('reservation.request');
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

        // Verifica conflitti con prenotazioni esistenti (solo se è stato assegnato un veicolo)
        if ($request->vehicle_id) {
            $vehicleId = $request->vehicle_id;

            // Verifica che il veicolo non sia in manutenzione
            $hasMaintenance = \App\Models\Maintenance::where('vehicle_id', $vehicleId)
                ->active()
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate->format('Y-m-d'))
                          ->where('end_date', '>=', $startDate->format('Y-m-d'));
                })
                ->exists();

            if ($hasMaintenance) {
                return redirect()->back()
                    ->with('error', 'Il veicolo è in manutenzione nel periodo selezionato. Scegliere un altro veicolo o periodo.');
            }

            // Verifica conflitti con prenotazioni esistenti giorno per giorno
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $existingReservation = Reservation::where('vehicle_id', $vehicleId)
                    ->whereDate('date', $date)
                    ->first();

                if ($existingReservation) {
                    return redirect()->back()
                        ->with('error', 'Il veicolo è già prenotato per il ' . $date->format('d/m/Y') . '. Scegliere un altro veicolo o periodo.');
                }
            }
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

        return redirect()->route('reservation.create')->with('success', 'Reservation created successfully.');
    }

    public function getReservations()
    {
        $request = Request();
        $date = Carbon::parse($request->input('date'));

        // Ottieni le prenotazioni per la data specificata
        $reservations = Reservation::whereDate('date', $date)
            ->where('status', '!=', 'maintenance')
            ->with('driver', 'vehicle')
            ->get();

        $data = $reservations->map(function ($reservation) {
            return [
                'driver' => $reservation->driver ?? "???",
                'vehicle' => $reservation->vehicle ?? "???",
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getTimelineData()
    {
        $request = Request();
        $startDate = Carbon::parse($request->input('start_date', Carbon::today()));
        $endDate = Carbon::parse($request->input('end_date', Carbon::today()->addDays(13)));

        // Ottieni tutti i veicoli disponibili
        $vehicles = Vehicle::where('status', 'available')
            ->orderBy('plate')
            ->get();

        // Genera array delle date nel range
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // Per ogni veicolo, ottieni TUTTE le prenotazioni per trovare date effettive
        $vehiclesData = $vehicles->map(function ($vehicle) use ($startDate, $endDate) {
            // Recupera tutte le prenotazioni per il veicolo
            $allReservations = Reservation::where('vehicle_id', $vehicle->id)
                ->with('driver')
                ->orderBy('date')
                ->get();

            // Raggruppa TUTTE le prenotazioni in booking consecutivi
            $allBookings = [];
            $currentBooking = null;

            foreach ($allReservations as $reservation) {
                $reservationDate = Carbon::parse($reservation->date);
                $driverName = $reservation->driver ? $reservation->driver->first_name . ' ' . $reservation->driver->last_name : 'Manutenzione';

                if ($currentBooking === null) {
                    $currentBooking = [
                        'driver_name' => $driverName,
                        'driver_id' => $reservation->driver_id,
                        'start_date' => $reservationDate->format('Y-m-d'),
                        'end_date' => $reservationDate->format('Y-m-d'),
                        'status' => $reservation->status,
                        'note' => $reservation->note,
                        'last_carbon_date' => $reservationDate,
                        'reservation_ids' => [$reservation->id],
                    ];
                } else {
                    $sameDriver = $currentBooking['driver_name'] === $driverName;
                    $isConsecutive = $currentBooking['last_carbon_date']->copy()->addDay()->isSameDay($reservationDate);

                    if ($sameDriver && $isConsecutive) {
                        $currentBooking['end_date'] = $reservationDate->format('Y-m-d');
                        $currentBooking['last_carbon_date'] = $reservationDate;
                        $currentBooking['reservation_ids'][] = $reservation->id;
                    } else {
                        unset($currentBooking['last_carbon_date']);
                        $allBookings[] = $currentBooking;
                        $currentBooking = [
                            'driver_name' => $driverName,
                            'driver_id' => $reservation->driver_id,
                            'start_date' => $reservationDate->format('Y-m-d'),
                            'end_date' => $reservationDate->format('Y-m-d'),
                            'status' => $reservation->status,
                            'note' => $reservation->note,
                            'last_carbon_date' => $reservationDate,
                            'reservation_ids' => [$reservation->id],
                        ];
                    }
                }
            }

            if ($currentBooking !== null) {
                unset($currentBooking['last_carbon_date']);
                $allBookings[] = $currentBooking;
            }

            // Filtra solo i booking che si sovrappongono al range visualizzato
            $bookings = [];
            foreach ($allBookings as $booking) {
                $bookingStart = Carbon::parse($booking['start_date']);
                $bookingEnd = Carbon::parse($booking['end_date']);

                // Il booking è rilevante se si sovrappone al range
                if ($bookingStart->lte($endDate) && $bookingEnd->gte($startDate)) {
                    // Salva le date effettive
                    $booking['actual_start_date'] = $booking['start_date'];
                    $booking['actual_end_date'] = $booking['end_date'];

                    // Limita le date di visualizzazione al range (per il posizionamento nella griglia)
                    if ($bookingStart->lt($startDate)) {
                        $booking['start_date'] = $startDate->format('Y-m-d');
                    }
                    if ($bookingEnd->gt($endDate)) {
                        $booking['end_date'] = $endDate->format('Y-m-d');
                    }

                    $bookings[] = $booking;
                }
            }

            return [
                'id' => $vehicle->id,
                'plate' => $vehicle->plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'logo' => $vehicle->logo ?? '',
                'bookings' => $bookings,
            ];
        });

        // Recupera le manutenzioni per il periodo
        $maintenances = \App\Models\Maintenance::whereIn('vehicle_id', $vehicles->pluck('id'))
            ->active()
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();

        // Aggiungi manutenzioni ai dati dei veicoli
        $vehiclesData = $vehiclesData->map(function($vehicleData) use ($maintenances, $startDate, $endDate) {
            $vehicleMaintenances = $maintenances->where('vehicle_id', $vehicleData['id']);

            $maintenanceBookings = $vehicleMaintenances->map(function($maintenance) use ($startDate, $endDate) {
                $actualStart = $maintenance->start_date->format('Y-m-d');
                $actualEnd = $maintenance->end_date->format('Y-m-d');

                // Limita le date al range visibile
                $visibleStart = max($actualStart, $startDate->format('Y-m-d'));
                $visibleEnd = min($actualEnd, $endDate->format('Y-m-d'));

                return [
                    'driver_name' => $maintenance->reason ?? 'Manutenzione',
                    'driver_id' => null,
                    'start_date' => $visibleStart,
                    'end_date' => $visibleEnd,
                    'actual_start_date' => $actualStart,
                    'actual_end_date' => $actualEnd,
                    'status' => 'maintenance',
                    'note' => $maintenance->description,
                    'maintenance_id' => $maintenance->id,
                    'is_maintenance' => true,
                ];
            })->toArray();

            // Unisci manutenzioni e prenotazioni
            $vehicleData['bookings'] = array_merge($vehicleData['bookings'], $maintenanceBookings);

            return $vehicleData;
        });

        return response()->json([
            'vehicles' => $vehiclesData,
            'dates' => $dates,
        ]);
    }

    public function updateBookingDates()
    {
        $request = Request();

        $request->validate([
            'reservation_ids' => 'required|array',
            'vehicle_id' => 'required|exists:vehicles,id',
            'new_start_date' => 'required|date',
            'new_end_date' => 'required|date|after_or_equal:new_start_date',
        ]);

        $reservationIds = $request->input('reservation_ids');
        $vehicleId = $request->input('vehicle_id');
        $newStartDate = Carbon::parse($request->input('new_start_date'));
        $newEndDate = Carbon::parse($request->input('new_end_date'));

        $firstReservation = Reservation::whereIn('id', $reservationIds)->first();

        if (!$firstReservation) {
            // La prenotazione potrebbe essere già stata modificata/eliminata
            return response()->json([
                'success' => true,
                'message' => 'Prenotazione già aggiornata'
            ]);
        }

        // Trova TUTTE le prenotazioni consecutive dello stesso driver/veicolo
        $driverId = $firstReservation->driver_id;
        $allConsecutiveIds = $this->findConsecutiveReservations($vehicleId, $driverId, $reservationIds);

        // Verifica conflitti escludendo TUTTE le prenotazioni consecutive
        $conflictingReservations = Reservation::where('vehicle_id', $vehicleId)
            ->whereNotIn('id', $allConsecutiveIds)
            ->whereBetween('date', [$newStartDate, $newEndDate])
            ->exists();

        if ($conflictingReservations) {
            return response()->json(['error' => 'Il veicolo non è disponibile per queste date'], 409);
        }

        // Verifica conflitti con manutenzioni
        $conflictingMaintenance = \App\Models\Maintenance::where('vehicle_id', $vehicleId)
            ->active()
            ->where(function($query) use ($newStartDate, $newEndDate) {
                $query->where('start_date', '<=', $newEndDate)
                      ->where('end_date', '>=', $newStartDate);
            })
            ->exists();

        if ($conflictingMaintenance) {
            return response()->json(['error' => 'Il veicolo è in manutenzione in questo periodo'], 409);
        }

        // Elimina TUTTE le prenotazioni consecutive (anche quelle fuori dalla griglia)
        Reservation::whereIn('id', $allConsecutiveIds)->delete();

        // Crea le nuove prenotazioni
        for ($date = $newStartDate->copy(); $date->lte($newEndDate); $date->addDay()) {
            Reservation::create([
                'vehicle_id' => $vehicleId,
                'driver_id' => $firstReservation->driver_id,
                'date' => $date->format('Y-m-d'),
                'status' => $firstReservation->status,
                'note' => $firstReservation->note,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Prenotazione aggiornata con successo'
        ]);
    }

    public function deleteBooking()
    {
        $request = Request();

        $request->validate([
            'reservation_ids' => 'required|array',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $reservationIds = $request->input('reservation_ids');
        $vehicleId = $request->input('vehicle_id');

        $firstReservation = Reservation::whereIn('id', $reservationIds)->first();

        if (!$firstReservation) {
            // La prenotazione potrebbe essere già stata modificata/eliminata
            return response()->json([
                'success' => true,
                'message' => 'Prenotazione già aggiornata'
            ]);
        }

        // Trova TUTTE le prenotazioni consecutive dello stesso driver/veicolo
        $driverId = $firstReservation->driver_id;
        $allConsecutiveIds = $this->findConsecutiveReservations($vehicleId, $driverId, $reservationIds);

        // Elimina TUTTE le prenotazioni consecutive
        Reservation::whereIn('id', $allConsecutiveIds)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prenotazione eliminata con successo'
        ]);
    }

    /**
     * Trova tutte le prenotazioni consecutive dello stesso driver/veicolo
     */
    private function findConsecutiveReservations($vehicleId, $driverId, $knownReservationIds)
    {
        // Ottieni tutte le prenotazioni del veicolo con lo stesso driver
        $reservations = Reservation::where('vehicle_id', $vehicleId)
            ->where('driver_id', $driverId)
            ->orderBy('date')
            ->get();

        // Trova le date delle prenotazioni note
        $knownDates = Reservation::whereIn('id', $knownReservationIds)
            ->pluck('date')
            ->map(function($date) {
                return Carbon::parse($date);
            })
            ->sort()
            ->values();

        if ($knownDates->isEmpty()) {
            return $knownReservationIds;
        }

        // Raggruppa tutte le prenotazioni in blocchi consecutivi
        $consecutiveBlocks = [];
        $currentBlock = null;

        foreach ($reservations as $reservation) {
            $resDate = Carbon::parse($reservation->date);

            if ($currentBlock === null) {
                $currentBlock = [
                    'ids' => [$reservation->id],
                    'last_date' => $resDate,
                ];
            } else {
                $isConsecutive = $currentBlock['last_date']->copy()->addDay()->isSameDay($resDate);

                if ($isConsecutive) {
                    $currentBlock['ids'][] = $reservation->id;
                    $currentBlock['last_date'] = $resDate;
                } else {
                    $consecutiveBlocks[] = $currentBlock;
                    $currentBlock = [
                        'ids' => [$reservation->id],
                        'last_date' => $resDate,
                    ];
                }
            }
        }

        if ($currentBlock !== null) {
            $consecutiveBlocks[] = $currentBlock;
        }

        // Trova il blocco che contiene almeno una delle prenotazioni note
        foreach ($consecutiveBlocks as $block) {
            foreach ($knownReservationIds as $knownId) {
                if (in_array($knownId, $block['ids'])) {
                    return $block['ids'];
                }
            }
        }

        // Fallback: ritorna solo quelle note
        return $knownReservationIds;
    }
}
