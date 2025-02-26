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



    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
