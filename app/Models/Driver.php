<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasActiveReservation()
    {
        return $this->reservations()
            ->whereDate('date', today())
            ->exists();
    }

    public function getAvailabilityAttribute()
    {
        return $this->hasActiveReservation() ? 'assigned' : 'available';
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('uuid', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
