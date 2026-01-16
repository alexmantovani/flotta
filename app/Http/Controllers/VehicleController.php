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
        $search = $request->input('search');
        $brand = $request->input('brand');
        $status = $request->input('status');

        $vehiclesQuery = Vehicle::query();

        // Search filter (targa, marca, modello, conducente)
        if ($search) {
            $vehiclesQuery->where(function ($query) use ($search) {
                $query->where('plate', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('reservations.driver', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Brand filter
        if ($brand) {
            $vehiclesQuery->where('brand', $brand);
        }

        $vehicles = $vehiclesQuery->get();

        // Status filter (apply after getting vehicles since availability is computed)
        if ($status) {
            $vehicles = $vehicles->filter(function ($vehicle) use ($status) {
                $availability = $vehicle->availability();
                if ($status === 'available') {
                    return $availability === 'available';
                } elseif ($status === 'unavailable') {
                    return $availability !== 'available';
                }
                return true;
            });
        }

        // Get unique brands for filter dropdown
        $brands = Vehicle::distinct()->pluck('brand')->sort()->values();

        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push(Carbon::today()->addDays($i));
        }

        return view('vehicle.index', compact('vehicles', 'dates', 'brands'));
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
        // Prenotazioni future (solo confirmed e pending)
        $groupedReservations = $vehicle->reservations()
            ->where('date', '>=', Carbon::today())
            ->whereIn('status', ['confirmed', 'pending'])
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

    /**
     * Import vehicles from CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $skipDuplicates = $request->has('skip_duplicates');
        $errors = [];
        $imported = 0;
        $skipped = 0;

        try {
            $file = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');

            // Read header row
            $header = fgetcsv($handle);

            if (!$header || !in_array('plate', $header) || !in_array('brand', $header) || !in_array('model', $header)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Il file CSV deve contenere almeno le colonne: plate, brand, model']
                ]);
            }

            // Get column indexes
            $plateIndex = array_search('plate', $header);
            $brandIndex = array_search('brand', $header);
            $modelIndex = array_search('model', $header);
            $statusIndex = array_search('status', $header);

            $rowNumber = 1;

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Validate required fields
                $plate = isset($row[$plateIndex]) ? trim($row[$plateIndex]) : null;
                $brand = isset($row[$brandIndex]) ? trim($row[$brandIndex]) : null;
                $model = isset($row[$modelIndex]) ? trim($row[$modelIndex]) : null;
                $status = ($statusIndex !== false && isset($row[$statusIndex])) ? trim($row[$statusIndex]) : 'available';

                if (empty($plate)) {
                    $errors[] = "Riga {$rowNumber}: Targa mancante";
                    continue;
                }

                if (empty($brand)) {
                    $errors[] = "Riga {$rowNumber}: Marca mancante";
                    continue;
                }

                if (empty($model)) {
                    $errors[] = "Riga {$rowNumber}: Modello mancante";
                    continue;
                }

                // Check for duplicates
                $existingVehicle = Vehicle::where('plate', $plate)->first();

                if ($existingVehicle) {
                    if ($skipDuplicates) {
                        $skipped++;
                        continue;
                    } else {
                        $errors[] = "Riga {$rowNumber}: Targa '{$plate}' già esistente";
                        continue;
                    }
                }

                // Create vehicle
                try {
                    Vehicle::create([
                        'plate' => $plate,
                        'brand' => $brand,
                        'model' => $model,
                        'status' => $status,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Riga {$rowNumber}: Errore durante la creazione del veicolo - " . $e->getMessage();
                }
            }

            fclose($handle);

            // Prepare success message
            $message = "Importati {$imported} veicoli con successo.";
            if ($skipped > 0) {
                $message .= " Saltati {$skipped} duplicati.";
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors,
                    'imported' => $imported,
                    'skipped' => $skipped
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['Errore durante la lettura del file: ' . $e->getMessage()]
            ]);
        }
    }
}
