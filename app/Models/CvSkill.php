<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvSkill extends Model
{

    use HasFactory;

    protected $fillable = [
        'cv_id',
        'title'
    ];


    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
