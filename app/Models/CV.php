<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CV extends Model
{
    /** @use HasFactory<\Database\Factories\CVFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'cv_path',
        'full_name',
        'summary',
        'image',
        'email',
        'phone_number',
        'gender',
        'language',
        'nationality',
        'birth_date',
        'skills',
        'education',
        'experience',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts()
    {
        return [
            'language' => 'array',
            'birth_date' => 'date'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CVDocument::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CVLink::class);
    }

}
