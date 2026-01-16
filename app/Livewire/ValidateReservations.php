<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Vehicle;
use Livewire\Component;
use Carbon\Carbon;

class ValidateReservations extends Component
{
    public $groupedReservations = [];
    public $vehicles;
    public $selectedVehicles = [];

    public function mount()
    {
        $this->loadReservations();
        $this->vehicles = Vehicle::all();
    }

    public function loadReservations()
    {
        $reservations = Reservation::where('status', 'pending')
            ->where('date', '>=', Carbon::today())
            ->with('driver')
            ->orderBy('driver_id', 'asc')
            ->orderBy('date', 'asc')
            ->get();

        // Raggruppa prenotazioni consecutive dello stesso conducente
        $this->groupedReservations = [];
        $currentGroup = null;

        foreach ($reservations as $reservation) {
            $reservationDate = Carbon::parse($reservation->date);

            if ($currentGroup === null) {
                // Inizia un nuovo gruppo
                $currentGroup = [
                    'driver_id' => $reservation->driver_id,
                    'driver' => $reservation->driver,
                    'vehicle_id' => $reservation->vehicle_id,
                    'start_date' => $reservationDate,
                    'end_date' => $reservationDate,
                    'note' => $reservation->note,
                    'user_id' => $reservation->user_id,
                    'reservation_ids' => [$reservation->id],
                    'last_carbon_date' => $reservationDate,
                ];
            } else {
                $sameDriver = $currentGroup['driver_id'] === $reservation->driver_id;
                $sameVehicle = $currentGroup['vehicle_id'] === $reservation->vehicle_id;
                $isConsecutive = $currentGroup['last_carbon_date']->copy()->addDay()->isSameDay($reservationDate);

                if ($sameDriver && $sameVehicle && $isConsecutive) {
                    // Aggiungi al gruppo corrente
                    $currentGroup['end_date'] = $reservationDate;
                    $currentGroup['last_carbon_date'] = $reservationDate;
                    $currentGroup['reservation_ids'][] = $reservation->id;
                } else {
                    // Salva il gruppo corrente e inizia uno nuovo
                    $groupKey = implode('-', $currentGroup['reservation_ids']);
                    $this->selectedVehicles[$groupKey] = $currentGroup['vehicle_id'];
                    unset($currentGroup['last_carbon_date']);
                    $this->groupedReservations[] = $currentGroup;

                    $currentGroup = [
                        'driver_id' => $reservation->driver_id,
                        'driver' => $reservation->driver,
                        'vehicle_id' => $reservation->vehicle_id,
                        'start_date' => $reservationDate,
                        'end_date' => $reservationDate,
                        'note' => $reservation->note,
                        'user_id' => $reservation->user_id,
                        'reservation_ids' => [$reservation->id],
                        'last_carbon_date' => $reservationDate,
                    ];
                }
            }
        }

        // Aggiungi l'ultimo gruppo
        if ($currentGroup !== null) {
            $groupKey = implode('-', $currentGroup['reservation_ids']);
            $this->selectedVehicles[$groupKey] = $currentGroup['vehicle_id'];
            unset($currentGroup['last_carbon_date']);
            $this->groupedReservations[] = $currentGroup;
        }
    }

    public function approveReservation($index)
    {
        \Log::info('Approva prenotazione chiamato', ['index' => $index]);

        // Verifica che l'utente sia admin
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Accesso negato.');
            return;
        }

        $group = $this->groupedReservations[$index];
        $groupKey = implode('-', $group['reservation_ids']);
        $selectedVehicleId = $this->selectedVehicles[$groupKey] ?? null;

        // Verifica che sia stato selezionato un veicolo
        if (!$selectedVehicleId) {
            session()->flash('error', 'Devi selezionare un veicolo prima di approvare.');
            return;
        }

        $startDate = $group['start_date'];
        $endDate = $group['end_date'];
        $reservationIds = $group['reservation_ids'];

        \Log::info('Approvazione prenotazioni', [
            'reservation_ids' => $reservationIds,
            'vehicle_id' => $selectedVehicleId,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);

        // Verifica conflitti con altre prenotazioni se il veicolo è cambiato
        if ($selectedVehicleId != $group['vehicle_id']) {
            // Verifica per ogni giorno del periodo (solo confirmed e pending)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $existingReservation = Reservation::where('vehicle_id', $selectedVehicleId)
                    ->whereDate('date', $date)
                    ->whereNotIn('id', $reservationIds)
                    ->whereIn('status', ['confirmed', 'pending'])
                    ->first();

                if ($existingReservation) {
                    session()->flash('error', 'Il veicolo selezionato è già prenotato per il ' . $date->format('d/m/Y'));
                    return;
                }
            }

            // Verifica conflitti con manutenzioni
            $hasMaintenance = \App\Models\Maintenance::where('vehicle_id', $selectedVehicleId)
                ->active()
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $startDate);
                })
                ->exists();

            if ($hasMaintenance) {
                session()->flash('error', 'Il veicolo selezionato è in manutenzione nel periodo richiesto.');
                return;
            }
        }

        // Aggiorna tutte le prenotazioni del gruppo
        $updated = Reservation::whereIn('id', $reservationIds)->update([
            'vehicle_id' => $selectedVehicleId,
            'status' => 'confirmed',
        ]);

        \Log::info('Prenotazioni aggiornate', ['count' => $updated]);

        $days = count($reservationIds);
        session()->flash('success', "Prenotazione approvata con successo ($days " . ($days == 1 ? 'giorno' : 'giorni') . ")");

        // Ricarica le prenotazioni
        $this->loadReservations();
    }

    public function rejectReservation($index)
    {
        // Verifica che l'utente sia admin
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Accesso negato.');
            return;
        }

        $group = $this->groupedReservations[$index];
        $reservationIds = $group['reservation_ids'];

        // Aggiorna lo status di tutte le prenotazioni del gruppo
        Reservation::whereIn('id', $reservationIds)->update([
            'status' => 'rejected',
        ]);

        $days = count($reservationIds);
        session()->flash('success', "Prenotazione rifiutata ($days " . ($days == 1 ? 'giorno' : 'giorni') . ")");

        // Ricarica le prenotazioni
        $this->loadReservations();
    }

    public function render()
    {
        return view('livewire.validate-reservations');
    }
}
