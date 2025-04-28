<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{


    protected $fillable = [
        'receiver_id',
        'sender_id',
        'advertisement_id',
        'status',
    ];


    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
