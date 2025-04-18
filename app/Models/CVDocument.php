<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvDocument extends Model
{
    /** @use HasFactory<\Database\Factories\CVDocumentFactory> */
    use HasFactory;


    protected $fillable = [
        'cv_id',
        'name',
        'path'
    ];


    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    
}
