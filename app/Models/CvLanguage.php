<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvLanguage extends Model
{
    protected $fillable = [
        'cv_id',
        'name',
        'rate'
    ];


    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
