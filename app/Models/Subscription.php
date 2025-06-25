<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'plan_id',
        'afford_price',
        'status',
        'starts_at',
        'ends_at',
        'number_of_ads'
    ];

    protected $hidden = [
        'user',

    ];


    protected $appends = [
       // 'created_from',

    ];


    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //! Accessories
    public function getCreatedFromAttribute()
    {

        Carbon::setLocale('ar');
        $diff = $this->created_at->locale('ar')->diffForHumans();
        return preg_replace('/(d+)/', '<strong>$1</strong>', $diff);
    }

    public function getUserNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function getPlanNameAttribute()
    {
        return $this->plan->name;
    }

    public function getPlanPriceAttribute()
    {
        return $this->plan->price;
    }


    public function toSimpleArray()
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'plan_name' => $this->plan_name,
            'plan_price' => $this->plan_price,
            'status' => $this->status,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'number_of_ads' => $this->number_of_ads,
            'created_from' => $this->created_from,

        ];
    }



}
