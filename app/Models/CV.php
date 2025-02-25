<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cv extends Model
{
    /** @use HasFactory<\Database\Factories\CvFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
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

    public function file():HasOne
    {
        return $this->hasOne(CvFile::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CvDocument::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CvLink::class);
    }

    public function qualification(): HasMany
    {

        return $this->hasMany(CvQualification::class);

    }

    public function experience(): HasMany
    {
        return $this->hasMany(CvExperience::class);
    }

    public function skill(): HasMany
    {
        return $this->hasMany(CvSkill::class);
    }
}
