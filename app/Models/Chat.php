<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        return $this->hasMany(Message::class);
    }


    public function getChatDetailsAttribute()
    {
        $user = Auth::user();
        $lastMessage = $this->messages()->latest()->first();

        $hasUnread = false;
        if ($lastMessage && $lastMessage->sender_id != $user->id) {

            $hasUnread = !$lastMessage->is_read;
        }

        return [
            'id' => $this->id,
            'sender_name' => $this->client->first_name,
            'sender_id' => $this->client->id,
            'receiver_name' => $this->seller->first_name,
            'receiver_id' => $this->seller->id,
            'price' => $this->ads->price,
            'city' => $this->ads->city_name,
            'location' => $this->ads->location,
            'advertisement_title' => $this->ads->title,
            'advertisement_image' => $this->ads->images,
            'created_from' => $this->created_from,
            'last_message' => $lastMessage ? $lastMessage->content : ' ',
            'has_unread' => $hasUnread //! if true thats meaning chat unreaded || false that chat readed
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
