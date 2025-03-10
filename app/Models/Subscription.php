<?php

namespace App\Models;

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
        'status',
        'starts_at',
        'ends_at'
    ];


    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
