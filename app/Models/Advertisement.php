<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'city_id',
        'category_id',
        'is_special',
        'status',
        'expiry_date'
    ];


    /**
     * The attributes that should be appends with user
     * @var array
     */
    protected $appends = [
        'attributes',
        'views',
        'created_from',
        'is_favorite',

    ];

    protected $hidden = [
        'deleted_at',
    ];
    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(AdvertisementAttribute::class);
    }

    //! Accessories

    public function getViewsAttribute()
    {
        return $this->views()->count();
    }

    public function getAttributesAttribute()
    {
        return $this->attributes()->get()->map(function ($attribute) {
            return [$attribute->name => $attribute->value];
        });
    }

    public function getIsFavoriteAttribute()
    {

        $user = Auth::user();

        if ($user) {
            return FavoriteListItem::where('advertisement_id', $this->id)
                ->whereHas('list', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->exists();
        }

        return false;
    }


}
