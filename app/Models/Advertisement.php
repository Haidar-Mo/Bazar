<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'city_id',
        'category_id',
        'is_special',
        'status'
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function views(): HasMany
    {
        return $this->HasMany(View::class);
    }



    //! Accessories
    
    public function getViewsAttribute()
    {
        return $this->views()->count();
    }



}
