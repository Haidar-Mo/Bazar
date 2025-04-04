<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'image',
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

    public function reported(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }



    //! Accessories

    public function getUserNameAttribute()
    {
        $data = $this->user()->first(['first_name', 'last_name'])
        ->makeHidden(['is_full_registered', 'rate', 'role', 'image', 'age', 'plan_name', 'notifications_count']);
        return $data->first_name . ' ' . $data->last_name;
    }

    public function getRatedUserNameAttribute()
    {

        $data = $this->ratedUser()->first(['first_name', 'last_name'])->makeHidden(['is_full_registered', 'rate', 'role', 'image', 'age', 'plan_name', 'notifications_count']);
        return $data->first_name . ' ' . $data->last_name;
    }

    public function getImageAttribute()
    {
        return $this->user()->first()->makeHidden(['is_full_registered', 'rate', 'role', 'age', 'plan_name', 'notifications_count'])->image ?? null;

    }

    public function getIsReportedAttribute()
    {
        return $this->reported()->where('is_read', false)->first()
            ?
            $this->reported()->where('is_read', false)->first()->user()->first()
            :
            false;
    }
}
