<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationRequest extends Model
{
    /** @use HasFactory<\Database\Factories\VerificationRequestFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'phone_number',
        'identity_image',
        'work_register',
        'profile_image',
        'company_name',
        'status',
        'reject_reason'
    ];

    protected $appends = [
        'identity_image_full_path',
        'work_register_full_path',
        'profile_image_full_path',
    ];

    public function getIdentityImageFullPathAttribute()
    {
        return $this->identity_image ? asset($this->identity_image) : null;
    }

    public function getWorkRegisterFullPathAttribute()
    {
        return $this->work_register ? asset($this->work_register) : null;
    }

    public function getProfileImageFullPathAttribute()
    {
        return $this->profile_image ? asset($this->profile_image) : null;
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
