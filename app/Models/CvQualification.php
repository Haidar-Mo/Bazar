<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvQualification extends Model
{
    use HasFactory;


    protected $fillable = [
        'cv_id',
        'certificate',
        'specialization',
        'university',
        'country',
        'entering_date',
        'graduation_date',
        'still_studying'
    ];

/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts()
    {
        return [
            'entering_date' => 'date:Y-m-d',
            'graduation_date' => 'date:Y-m-d'
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
