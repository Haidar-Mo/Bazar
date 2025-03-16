<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertisementAttribute extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementAttributeFactory> */
    use HasFactory;



    protected $fillable = [
        'advertisement_id',
        'title',
        'name',
        'value'
    ];


    public function ads(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }


}
