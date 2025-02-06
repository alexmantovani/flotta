<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;

class ValidateReservationRow extends Component
{
    public $reservation;
    public $vehicles;
    private $visible = true;

    public function approveReservation()
    {
        dd("APPROVE RESERVATION");
        // TODO: Verifico che la macchina sia disponibile per il giorno selezionato

        $this->reservation->vehicle_id = $vehicleId;
        $this->reservation->status = 'confirmed';
        $this->reservation->save();

        $this->visible = false;
    }

    public function rejectReservation()
    {
        $this->reservation->status = 'rejected';
        $this->reservation->save();

        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.validate-reservation-row');
    }
}
