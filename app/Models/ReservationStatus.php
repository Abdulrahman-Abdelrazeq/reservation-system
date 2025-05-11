<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationStatus extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING = 1;
    const CONFIRMED = 2;
    const CANCELLED = 3;

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'status_id');
    }
}
