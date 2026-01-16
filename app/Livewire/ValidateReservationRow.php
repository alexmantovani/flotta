<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;
use Carbon\Carbon;

class ValidateReservationRow extends Component
{
    public $reservationGroup;
    public $vehicles;
    public $selectedVehicleId;

    public function mount()
    {
        $this->selectedVehicleId = $this->reservationGroup['vehicle_id'];
    }

    public function approveReservation()
    {
        \Log::info('Approva prenotazione chiamato');

        // Verifica che l'utente sia admin
        if (!auth()->user()->isAdmin()) {
            \Log::error('Utente non admin');
            session()->flash('error', 'Accesso negato.');
            return redirect()->route('reservation.validate');
        }

        // Verifica che sia stato selezionato un veicolo
        if (!$this->selectedVehicleId) {
            \Log::error('Nessun veicolo selezionato');
            session()->flash('error', 'Devi selezionare un veicolo prima di approvare.');
            return redirect()->route('reservation.validate');
        }

        $startDate = $this->reservationGroup['start_date'];
        $endDate = $this->reservationGroup['end_date'];
        $reservationIds = $this->reservationGroup['reservation_ids'];

        \Log::info('Approvazione prenotazioni', [
            'reservation_ids' => $reservationIds,
            'vehicle_id' => $this->selectedVehicleId,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);

        // Verifica conflitti con altre prenotazioni se il veicolo è cambiato
        if ($this->selectedVehicleId != $this->reservationGroup['vehicle_id']) {
            // Verifica per ogni giorno del periodo (solo confirmed e pending)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $existingReservation = Reservation::where('vehicle_id', $this->selectedVehicleId)
                    ->whereDate('date', $date)
                    ->whereNotIn('id', $reservationIds)
                    ->whereIn('status', ['confirmed', 'pending'])
                    ->first();

                if ($existingReservation) {
                    \Log::error('Conflitto prenotazione trovato', ['date' => $date->format('Y-m-d')]);
                    session()->flash('error', 'Il veicolo selezionato è già prenotato per il ' . $date->format('d/m/Y'));
                    return redirect()->route('reservation.validate');
                }
            }

            // Verifica conflitti con manutenzioni
            $hasMaintenance = \App\Models\Maintenance::where('vehicle_id', $this->selectedVehicleId)
                ->active()
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $startDate);
                })
                ->exists();

            if ($hasMaintenance) {
                \Log::error('Manutenzione in conflitto');
                session()->flash('error', 'Il veicolo selezionato è in manutenzione nel periodo richiesto.');
                return redirect()->route('reservation.validate');
            }
        }

        // Aggiorna tutte le prenotazioni del gruppo
        $updated = Reservation::whereIn('id', $reservationIds)->update([
            'vehicle_id' => $this->selectedVehicleId,
            'status' => 'confirmed',
        ]);

        \Log::info('Prenotazioni aggiornate', ['count' => $updated]);

        $days = count($reservationIds);
        session()->flash('success', "Prenotazione approvata con successo ($days " . ($days == 1 ? 'giorno' : 'giorni') . ")");

        // Redirect per ricaricare la pagina
        return redirect()->route('reservation.validate');
    }

    public function rejectReservation()
    {
        // Verifica che l'utente sia admin
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Accesso negato.');
            return redirect()->route('reservation.validate');
        }

        $reservationIds = $this->reservationGroup['reservation_ids'];

        // Aggiorna lo status di tutte le prenotazioni del gruppo
        Reservation::whereIn('id', $reservationIds)->update([
            'status' => 'rejected',
        ]);

        $days = count($reservationIds);
        session()->flash('success', "Prenotazione rifiutata ($days " . ($days == 1 ? 'giorno' : 'giorni') . ")");

        // Redirect per ricaricare la pagina
        return redirect()->route('reservation.validate');
    }

    public function render()
    {
        return view('livewire.validate-reservation-row');
    }
}
