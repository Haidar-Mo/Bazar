<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'duration',
        'size',
        'price',
        'discount_price',
        'details',
        'is_special'
    ];

    protected $appends = [
        'is_special_text'
    ];

    public function subscription(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }


    //! Accessories

    public function getIsSpecialTextAttribute()
    {
        return $this->is_special ? 'مميزة' : 'عادية';
    }

}
