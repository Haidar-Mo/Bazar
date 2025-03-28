<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'advertisement_id'
    ];

    protected $appends = ['created_from'];
    protected $hidden = [
        'ads'
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
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }


    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }


    public function getChatDetailsAttribute()
    {

        return [
            'id' => $this->id,
            'sender_name' => $this->client->first_name,
            'sender_id' => $this->client->id,
            'receiver_name' => $this->seller->first_name,
            'receiver_id' => $this->seller->id,
            'advertisement_title' => $this->ads->title,
            'advertisement_image' => $this->ads->images,
            'created_from' => $this->created_from
        ];
    }


    public function getAdvertisementDetailsAttribute()
    {
        return [
            'id' => $this->ads->id,
            'title' => $this->ads->title,
            'price' => $this->ads->price,
            'currency_type' => $this->ads->currency_type,
            'city' => $this->ads->city_name,
            'advertisement_image' => $this->ads->images,
            'created_from' => $this->created_from
        ];
    }


    public function getCreatedFromAttribute()
    {

        Carbon::setLocale('ar');
        $diff = $this->created_at->locale('ar')->diffForHumans();
        return preg_replace('/(d+)/', '<strong>$1</strong>', $diff);
    }
}
