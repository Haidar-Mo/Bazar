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
        'other_document',
        'status',
        'reject_reason'
    ];

    protected $appends = [
        'identity_image_full_path',
        'work_register_full_path',
        'other_document_full_path',
    ];

    public function getIdentityImageFullPathAttribute()
    {
        return $this->identity_image_path ? asset('storage/' . $this->identity_image_path) : null;
    }

    public function getWorkRegisterFullPathAttribute()
    {
        return $this->work_register_path ? asset('storage/' . $this->work_register_path) : null;
    }

    public function getOtherDocumentFullPathAttribute()
    {
        return $this->other_document_path ? asset('storage/' . $this->other_document_path) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
