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
        'paragraph',
        'is_read',
    ];

    protected $appends = [
        'reporter_user_name',
        'reported_user_name',
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

    public function getReporterUserNameAttribute()
    {
        $user = $this->user()->first();
        return "$user->first_name $user->last_name";
    }
    public function getReportedUserNameAttribute()
    {
        if ($this->reportable_type == "App\\Models\\User") {
            $user = User::findOrFail($this->reportable_id);
            return "$user->first_name $user->last_name";
        } else if ($this->reportable_type == "App\\Models\\Rate") {
            $rate = Rate::findOrFail($this->reportable_id);
            $user = $rate->user()->first();
            return "$user->first_name $user->last_name";
        } else if ($this->reportable_type == "App\\Models\\Advertisement") {
            $rate = Advertisement::findOrFail($this->reportable_id);
            $user = $rate->user()->first();
            return "$user->first_name $user->last_name";
        } else {

            return "???";
        }
    }
}
