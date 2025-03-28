<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;


    protected $fillable = [
        'chat_id',
        'sender_id',
        'content'
    ];

    protected $appends = ['created_from'];


    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function getCreatedFromAttribute()
    {

        Carbon::setLocale('ar');
        $diff = $this->created_at->locale('ar')->diffForHumans();
        return preg_replace('/(d+)/', '<strong>$1</strong>', $diff);
    }
}
