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
        'price'
    ];


    public function subscription(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }


}
