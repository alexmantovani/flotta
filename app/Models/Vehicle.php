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
        return $this->getReservationForDate($date) === null;
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

        // Ottieni tutti i veicoli con stato 'available'
        $vehicles = self::where('status', 'available')->orderBy('plate')->get();

        // Filtra i veicoli disponibili per il periodo specificato
        $availableVehicles = $vehicles->filter(function ($vehicle) use ($dates) {
            // Verifica se il veicolo ha prenotazioni che si sovrappongono
            $hasOverlappingReservations = $vehicle->reservations->contains(function ($reservation) use ($dates) {
                return $dates->contains(function ($date) use ($reservation) {
                    return $reservation->date->isSameDay($date);
                });
            });

            // Restituisci solo i veicoli che non hanno prenotazioni sovrapposte
            return !$hasOverlappingReservations;
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

    $reservations = $this->reservations()
        ->whereDate('date', $date)
        ->get(['status']);

    if ($reservations->contains('status', 'maintenance')) {
        return "maintenance";
    }

    if ($reservations->isNotEmpty()) {
        return "reserved";
    }

    return "available";
}

}
