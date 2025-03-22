<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'reportable_id',
        'reportable_type',
        'status',
        'paragraph'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    //! Accessories


}
