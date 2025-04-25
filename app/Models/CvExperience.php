<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvExperience extends Model
{

    use HasFactory;

    protected $fillable = [
        'cv_id',
        'job_name',
        'job_type',
        'company_sector',
        'company_name',
        'country',
        'job_description',
        'start_date',
        'end_date',
        'current_job',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts()
    {
        return [
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d'
        ];
    }
    
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
