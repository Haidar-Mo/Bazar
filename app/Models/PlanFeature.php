<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFeatureFactory> */
    use HasFactory;


    protected $fillable = [
        'plan_id',
        'name',
        'value'
    ];



    public function  plan():BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
