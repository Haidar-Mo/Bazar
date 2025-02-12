<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{

    use HasApiTokens ,HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'birth_date',
        'gender',
        'address',
        'device_token',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be appends with user
     * @var array
     */
    protected $appends = [
        'rate'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    /*public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }*/

    public function cv(): HasOne
    {
        return $this->hasOne(CV::class);
    }

    public function chat(): HasMany
    {
        return $this->hasMany(Chat::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function verifyRequest(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function report(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function reportable(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /*public function rate(): HasMany
    {
        return $this->hasMany(Rate::class);
    }*/

    public function rated(): HasMany
    {
        return $this->hasMany(Rate::class, 'rated_user_id');
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function favorite(): HasMany
    {
        return $this->hasMany(FavoriteList::class);
    }


    public function NotificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSetting::class);
    }




    //! Accessories 

    public function getRateAttribute()
    {
        return $this->rated()->count() > 0 ? $this->rated()->sum('rate') / $this->rated()->count() : 0;
    }
}
