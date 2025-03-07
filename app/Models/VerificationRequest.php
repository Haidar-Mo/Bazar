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


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
