<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'advertisement_id',
        'total_cost',
        'status', //: pending, accepted, rejected
    ];

    protected $appends = ['user_name', 'advertisement_title'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function reservationDates()
    {
        return $this->hasMany(ReservationDates::class);
    }


    //! Accessories

    public function getUserNameAttribute()
    {
        $user = $this->user()->first();
        return "$user->first_name $user->last_name";
    }

    public function getAdvertisementTitleAttribute()
    {
        $advertisement = $this->advertisement()->first();
        return $advertisement->title;
    }
}
