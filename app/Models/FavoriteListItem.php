<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteListItem extends Model
{

    use HasFactory;


    protected $fillable = [
        'favorite_list_id',
        'advertisement_id'
    ];


    public function list(): BelongsTo
    {
        return $this->belongsTo(FavoriteList::class,'favorite_list_id');
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class,'advertisement_id');
    }

}
