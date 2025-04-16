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

    protected $appends = [
        'full_path',
        'file_name'
    ];
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    //! Accessories
    public function getFullPathAttribute()
    {
        return asset($this->url);
    }


    public function getFileNameAttribute()
    {
        return basename($this->url);
    }
}
