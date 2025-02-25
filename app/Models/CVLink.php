<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvLink extends Model
{
    /** @use HasFactory<\Database\Factories\CVLinkFactory> */
    use HasFactory;

    protected $fillable = [
        'cv_id',
        'name',
        'link'
    ];



    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
