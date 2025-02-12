<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PendingUser extends Model
{
    use Notifiable;

    protected $fillable = [
        'email',
        'verification_code',
        'verification_code_expires_at',
        'verified_at'
    ];
}
