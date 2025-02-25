<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvFile extends Model
{
    /** @use HasFactory<\Database\Factories\CvFileFactory> */
    use HasFactory;


    protected $fillable = [
        'cv_id',
        'url'
    ];


    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
