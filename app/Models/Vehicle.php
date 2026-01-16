<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function activeMaintenances()
    {
        return $this->hasMany(Maintenance::class)
            ->active()
            ->orderBy('start_date');
    }

    public function getReservationForDate($date)
    {
        // Assicurati che la data sia un'istanza di Carbon
        $date = \Carbon\Carbon::parse($date);

        return $this->reservations->first(function ($reservation) use ($date) {
            return $reservation->date->isSameDay($date);
        });
    }

    public function isAvailableForDate($date)
    {
        // Verifica se il veicolo ha una prenotazione in quella data
        $hasReservation = $this->getReservationForDate($date) !== null;

        if ($hasReservation) {
            return false;
        }

        // Verifica se il veicolo è in manutenzione in quella data
        $hasMaintenance = $this->maintenances()
            ->active()
            ->where(function($query) use ($date) {
                $query->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date);
            })
            ->exists();

        return !$hasMaintenance;
    }

    // public static function getAvailableVehicle($startDate, $endDate)
    // {
    //     // Assicurati che le date siano istanze di Carbon
    //     $startDate = Carbon::parse($startDate);
    //     $endDate = Carbon::parse($endDate);

    //     // Ottieni tutti i veicoli
    //     $vehicles = self::orderBy('plate')->get();

    //     foreach ($vehicles as $vehicle) {
    //         // Verifica se il veicolo ha prenotazioni che si sovrappongono all'intervallo di date specificato
    //         $hasOverlappingReservations = $vehicle->reservations->contains(function ($reservation) use ($startDate, $endDate) {
    //             return $reservation->date->between($startDate, $endDate);
    //         });

    //         // Se il veicolo non ha prenotazioni sovrapposte, è disponibile
    //         if (!$hasOverlappingReservations) {
    //             return $vehicle;
    //         }
    //     }

    //     // Nessun veicolo disponibile trovato
    //     return null;
    // }

    // public static function getAvailableVehicle($dates)
    // {
    //     // Assicurati che le date siano un array di istanze di Carbon
    //     $dates = collect($dates)->map(function ($date) {
    //         return Carbon::parse($date);
    //     });

    //     // Ottieni tutti i veicoli
    //     $vehicles = self::where('status', 'available')->orderBy('plate')->get();

    //     foreach ($vehicles as $vehicle) {
    //         // Verifica se il veicolo ha prenotazioni che si sovrappongono a una qualsiasi delle date specificate
    //         $hasOverlappingReservations = $vehicle->reservations->contains(function ($reservation) use ($dates) {
    //             return $dates->contains(function ($date) use ($reservation) {
    //                 return $reservation->date->isSameDay($date);
    //             });
    //         });

    //         // Se il veicolo non ha prenotazioni sovrapposte, è disponibile
    //         if (!$hasOverlappingReservations) {
    //             return $vehicle;
    //         }
    //     }

    //     // Nessun veicolo disponibile trovato
    //     return null;
    // }
    public static function getAvailableVehicle($dates)
    {
        // Assicurati che le date siano un array di istanze di Carbon
        $dates = collect($dates)->map(function ($date) {
            return Carbon::parse($date);
        });

        // Ottieni tutti i veicoli con stato 'available', caricando solo le prenotazioni confirmed e pending
        $vehicles = self::where('status', 'available')
            ->with(['reservations' => function ($query) {
                $query->whereIn('status', ['confirmed', 'pending']);
            }])
            ->orderBy('plate')
            ->get();

        // Filtra i veicoli disponibili per il periodo specificato
        $availableVehicles = $vehicles->filter(function ($vehicle) use ($dates) {
            // Verifica se il veicolo ha prenotazioni che si sovrappongono (solo confirmed e pending)
            $hasOverlappingReservations = $vehicle->reservations->contains(function ($reservation) use ($dates) {
                return $dates->contains(function ($date) use ($reservation) {
                    return $reservation->date->isSameDay($date);
                });
            });

            if ($hasOverlappingReservations) {
                return false;
            }

            // Verifica manutenzioni sovrapposte
            foreach ($dates as $date) {
                $hasMaintenance = $vehicle->maintenances()
                    ->active()
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->exists();

                if ($hasMaintenance) {
                    return false;
                }
            }

            return true;
        });

        // Se non ci sono veicoli disponibili, restituisci null
        if ($availableVehicles->isEmpty()) {
            return null;
        }

        // Scegli un veicolo a caso tra quelli disponibili
        return $availableVehicles->random();
    }

    // public function getAvailabilityAttribute()
    // {
    //     if ($this->status != 'available') {
    //         return $this->status; // Restituisce lo stato del veicolo se non è disponibile
    //     }

    //     $todayReservations = $this->reservations()
    //         ->whereDate('date', today())
    //         ->get(['status']);

    //     if ($todayReservations->contains('status', 'maintenance')) {
    //         return "maintenance";
    //     }

    //     if ($todayReservations->isNotEmpty()) {
    //         return "reserved";
    //     }

    //     return "available";
    // }

    public function availability($date = null)
{
    $date = $date ?? today(); // Se $date è null, usa la data di oggi

    if ($this->status != 'available') {
        return $this->status; // Restituisce lo stato del veicolo se non è disponibile
    }

    // Verifica se c'è una manutenzione attiva per questa data
    $hasMaintenance = $this->maintenances()
        ->active()
        ->where('start_date', '<=', $date)
        ->where('end_date', '>=', $date)
        ->exists();

    if ($hasMaintenance) {
        return "maintenance";
    }

    // Verifica le prenotazioni (solo confirmed e pending)
    $reservations = $this->reservations()
        ->whereDate('date', $date)
        ->whereIn('status', ['confirmed', 'pending'])
        ->get(['status']);

    if ($reservations->isNotEmpty()) {
        return "reserved";
    }

    return "available";
}

}
