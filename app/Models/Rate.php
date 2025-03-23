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
        'rate',
        'comment'
    ];


    protected $appends = [
        'user_name',
        'rated_user_name'
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

    public function getUserNameAttribute()
    {
        $data = $this->user()->first(['first_name', 'last_name'])->makeHidden(['is_full_registered', 'rate', 'role','image','age','plan_name','notifications_count']);
        return $data->first_name .' '. $data->last_name;
    }

    public function getRatedUserNameAttribute()
    {

        $data = $this->ratedUser()->first(['first_name', 'last_name'])->makeHidden(['is_full_registered', 'rate', 'role','image','age','plan_name','notifications_count']);
        return $data->first_name .' '. $data->last_name;    }
}
