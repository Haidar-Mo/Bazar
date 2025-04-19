<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationSettingFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'category_id',
        'is_active',
        'type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends=[

        'category_name'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //! Accessories

    public function getCategoryNameAttribute(){
        return $this->category()->first()->name;
    }
}
