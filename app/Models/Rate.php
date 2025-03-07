<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    /** @use HasFactory<\Database\Factories\RateFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'rated_user_id',
        'rete',
        'comment'
    ];


    protected $appends = [
        'user'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ratedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }



    //! Accessories

    public function getUserAttribute()
    {
        return $this->user()->get(['first_name', 'last_name'])->makeHidden(['is_full_registered','rate','role']);
    }
}
