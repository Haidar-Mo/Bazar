<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\{
    HasPermissions,
    HasRoles
};

class User extends Authenticatable
{


    use HasApiTokens, HasFactory, HasRoles, HasPermissions, Notifiable;
    protected $guard = 'api';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'address',
        'gender',
        'job',
        'company',
        'description',
        'is_verified',
        'is_blocked',
        'block_reason',
        'provider',
        'provider_id',
        'device_token',
        'email_verified_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
        'permissions'
    ];

    /**
     * The attributes that should be appends with user
     * @var array
     */
    protected $appends = [
        'role',
        'image',
        'plan_name',
        'is_full_registered',
        'is_verified_text',
        'rate',
        'notifications_count',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'date:Y-m-d',
            'updated_at' => 'date:Y-m-d',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

        ];
    }


    public function ads(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function cv(): HasOne
    {
        return $this->hasOne(Cv::class);
    }

    public function chat(): HasMany
    {
        return $this->hasMany(Chat::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);

    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function verificationRequest(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function reported(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'user_id');
    }
    public function rated(): HasMany
    {
        return $this->hasMany(Rate::class, 'rated_user_id');
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function jobRequest(): HasMany
    {
        return $this->hasMany(JobRequest::class, 'receiver_id');
    }

    public function sendedJobRequest(): HasMany
    {
        return $this->hasMany(JobRequest::class, 'sender_id');

    }

    public function favorite(): HasMany
    {
        return $this->hasMany(FavoriteList::class);
    }

    public function NotificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSetting::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function appointment()
    {
        return $this->hasMany(AdvertisementAppointment::class, 'user_owner_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    //! Accessories

    public function getRoleAttribute()
    {

        return $this->roles()->first() ? $this->roles()->first()->name : 'guest';
    }

    public function getAllPermissionsAttribute()
    {
        $permissions = $this->getAllPermissions()->pluck('name');
        return $permissions->isEmpty() ? [] : $permissions;
    }


    public function getHasVerificationRequestAttribute()
    {
        return $this->verificationRequest()->where('status', 'pending')->first()->id ?? null;
    }

    public function getImageAttribute()
    {
        return $this->images()->first()->full_path ?? null;
    }

    public function getPlanNameAttribute()
    {
        $sub = $this->subscriptions()->where('status', 'running')->latest()->first();
        return $sub ? $sub->plan()->first()->name : null;
    }


    public function getIsFullRegisteredAttribute()
    {
        return $this->first_name && $this->last_name ? true : false;
    }
    public function getRateAttribute()
    {
        return $this->rated()->count() > 0 ? number_format($this->rated()->sum('rate') / $this->rated()->count(), 1) : 0;
    }

    public function getIsReportedAttribute()
    {
        return $this->reported()->where('is_read', false)->first()
            ?
            $this->reported()->where('is_read', false)->first()->user()->first()
            :
            false;
    }

    public function getNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count() ?? 0;
    }

    public function getIsVerifiedTextAttribute()
    {
        return $this->is_verified ? 'موثوق' : 'غير موثوق';
    }
}
