<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'advertisement_id'
    ];


    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }


    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

}
