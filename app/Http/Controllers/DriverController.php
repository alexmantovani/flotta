<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $availability = $request->input('availability');

        $driversQuery = Driver::query();

        // Search filter (nome, cognome, matricola, email)
        if ($search) {
            $driversQuery->search($search);
        }

        $drivers = $driversQuery->get();

        // Availability filter (apply after getting drivers since availability is computed)
        if ($availability) {
            $drivers = $drivers->filter(function ($driver) use ($availability) {
                return $driver->availability === $availability;
            });
        }

        return view('driver.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('driver.edit', ['driver' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'uuid' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:255',
        ]);

        Driver::create($request->all());

        return redirect()->route('driver.index')->with('success', 'Conducente creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        // Future reservations
        $upcomingReservations = $driver->reservations()
            ->where('date', '>=', Carbon::today())
            ->with('vehicle')
            ->orderBy('date')
            ->get()
            ->groupBy(function ($reservation) {
                return Carbon::parse($reservation->date)->format('Y-m');
            });

        // Past reservations (last 10)
        $pastReservations = $driver->reservations()
            ->where('date', '<', Carbon::today())
            ->with('vehicle')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // Current reservation (today)
        $currentReservation = $driver->reservations()
            ->whereDate('date', today())
            ->with('vehicle')
            ->first();

        return view('driver.show', compact(
            'driver',
            'upcomingReservations',
            'pastReservations',
            'currentReservation'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        return view('driver.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'uuid' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:255',
        ]);

        $driver->update($request->all());

        return redirect()->route('driver.show', $driver)->with('success', 'Conducente aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('driver.index')->with('success', 'Conducente eliminato con successo.');
    }

    /**
     * Import drivers from CSV file.
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

            if (!$header || !in_array('first_name', $header) || !in_array('last_name', $header)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Il file CSV deve contenere almeno le colonne: first_name, last_name']
                ]);
            }

            // Get column indexes
            $firstNameIndex = array_search('first_name', $header);
            $lastNameIndex = array_search('last_name', $header);
            $uuidIndex = array_search('uuid', $header);
            $emailIndex = array_search('email', $header);
            $phoneIndex = array_search('phone_number', $header);

            $rowNumber = 1;

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Validate required fields
                $firstName = isset($row[$firstNameIndex]) ? trim($row[$firstNameIndex]) : null;
                $lastName = isset($row[$lastNameIndex]) ? trim($row[$lastNameIndex]) : null;
                $uuid = ($uuidIndex !== false && isset($row[$uuidIndex])) ? trim($row[$uuidIndex]) : null;
                $email = ($emailIndex !== false && isset($row[$emailIndex])) ? trim($row[$emailIndex]) : null;
                $phoneNumber = ($phoneIndex !== false && isset($row[$phoneIndex])) ? trim($row[$phoneIndex]) : null;

                if (empty($firstName)) {
                    $errors[] = "Riga {$rowNumber}: Nome mancante";
                    continue;
                }

                if (empty($lastName)) {
                    $errors[] = "Riga {$rowNumber}: Cognome mancante";
                    continue;
                }

                // Check for duplicates by email (if provided)
                if ($email) {
                    $existingDriver = Driver::where('email', $email)->first();

                    if ($existingDriver) {
                        if ($skipDuplicates) {
                            $skipped++;
                            continue;
                        } else {
                            $errors[] = "Riga {$rowNumber}: Email '{$email}' già esistente";
                            continue;
                        }
                    }
                }

                // Create driver
                try {
                    Driver::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'uuid' => $uuid,
                        'email' => $email,
                        'phone_number' => $phoneNumber,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Riga {$rowNumber}: Errore durante la creazione del conducente - " . $e->getMessage();
                }
            }

            fclose($handle);

            // Prepare success message
            $message = "Importati {$imported} conducenti con successo.";
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
