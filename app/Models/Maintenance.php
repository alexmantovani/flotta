<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'start_date',
        'end_date',
        'reason',
        'description',
        'type',
        'status',
        'cost',
        'provider',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
    ];

    // Relazione con Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Scope per manutenzioni attive
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['scheduled', 'in_progress']);
    }

    // Scope per manutenzioni future
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', Carbon::today())
            ->where('status', 'scheduled');
    }

    // Scope per manutenzioni in corso
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress')
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today());
    }

    // Scope per storico
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('end_date', 'desc');
    }

    // Verifica se una data è coperta da questa manutenzione
    public function coversDate($date)
    {
        $checkDate = Carbon::parse($date);
        return $checkDate->between($this->start_date, $this->end_date);
    }

    // Verifica sovrapposizione con un periodo
    public function overlaps($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return $this->start_date->lte($end) && $this->end_date->gte($start);
    }

    // Calcola durata in giorni
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // Verifica se è attiva
    public function getIsActiveAttribute()
    {
        return in_array($this->status, ['scheduled', 'in_progress']);
    }
}
