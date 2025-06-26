<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementAppointment extends Model
{


    protected $fillable = [
        'user_id',
        'user_owner_id',
        'advertisement_id',
        'date',
        'time',
        'note',
        'status' //: pending , accepted , rejected
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function userOwner()
    {
        return $this->belongsTo(User::class, 'user_owner_id');
    }


    //! Accessories
}
