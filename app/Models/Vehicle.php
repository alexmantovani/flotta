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

    public static function getAvailableVehicle($dates)
    {
        // Assicurati che le date siano un array di istanze di Carbon
        $dates = collect($dates)->map(function ($date) {
            return Carbon::parse($date);
        });

        // Ottieni tutti i veicoli
        $vehicles = self::where('status', 'available')->orderBy('plate')->get();

        foreach ($vehicles as $vehicle) {
            // Verifica se il veicolo ha prenotazioni che si sovrappongono a una qualsiasi delle date specificate
            $hasOverlappingReservations = $vehicle->reservations->contains(function ($reservation) use ($dates) {
                return $dates->contains(function ($date) use ($reservation) {
                    return $reservation->date->isSameDay($date);
                });
            });

            // Se il veicolo non ha prenotazioni sovrapposte, è disponibile
            if (!$hasOverlappingReservations) {
                return $vehicle;
            }
        }

        // Nessun veicolo disponibile trovato
        return null;
    }
}
