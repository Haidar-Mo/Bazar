<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationDates extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationDatesFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'date'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
