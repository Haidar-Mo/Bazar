<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id'
    ];
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }


    public function notificationSetting(): HasMany
    {
        return $this->hasMany(NotificationSetting::class);
    }

    public static function scopeParent(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

}
