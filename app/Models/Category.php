<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'parent_id'
    ];



    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }


    public function notificationSetting(): HasMany
    {
        return $this->hasMany(NotificationSetting::class);
    }

}
