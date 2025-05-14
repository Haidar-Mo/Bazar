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
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'city_id',
        'category_id',
        'title',
        'type',
        'price',
        'currency_type',
        'location',
        'negotiable',
        'is_special',
        'status',    //: 'active', 'inactive', 'rejected', 'pending'
        'expiry_date'
    ];


    /**
     * The attributes that should be appends with user
     * @var array
     */
    protected $appends = [
        'user_name',
        'user_number',
        'city_name',
        'main_category_id',
        'main_category_name',
        'category_name',
        'is_negotiable',
        'created_from',
        'updated_from',
        'views',
        'is_favorite',
        'images',
    ];

    protected $hidden = [
        'deleted_at',
        'category'
    ];

    public function casts()
    {
        return [
            'is_special' => 'boolean',
            'negotiable' => 'boolean'
        ];
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

    public function jobRequest(): HasMany
    {
        return $this->hasMany(JobRequest::class);
    }

    public function items()
    {
        return $this->hasMany(FavoriteListItem::class);
    }

    public function reported(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    //! Accessories

    public function getUserNameAttribute()
    {
        return $this->user()->first()->first_name . ' ' . $this->user()->first()->last_name;
    }

    public function getUserNumberAttribute()
    {
        return $this->user()->first()->phone_number ?? null;
    }


    public function getCityNameAttribute()
    {
        return $this->city()->first()->name;
    }

    public function getCategoryNameAttribute()
    {
        return $this->category()->first()->name;
    }

    public function getViewsAttribute()
    {
        return $this->views()->count();
    }

    public function getImagesAttribute()
    {
        return $this->images()->get();
    }
    public function getAttributesAttribute()
    {
        $attributes = $this->attributes()->get();
        return $attributes->groupBy('title')
            ->map(function ($group) {
                // Group by name first to handle potential multiple values
                $groupedByName = $group->groupBy('name');

                return $groupedByName->map(function ($items) {
                    // If multiple items share the same name (like multiple comfort features)
                    if ($items->count() > 1) {
                        return $items->pluck('value')->toArray();
                    }
                    // Single value items
                    return $items->first()->value;
                });
            });
    }

    public function getCreatedFromAttribute()
    {

        Carbon::setLocale('ar');
        $diff = $this->created_at->locale('ar')->diffForHumans();
        return preg_replace('/(d+)/', '<strong>$1</strong>', $diff);
    }
    public function getUpdatedFromAttribute()
    {
        Carbon::setLocale('ar');
        $diff = $this->updated_at->locale('ar')->diffForHumans();
        return preg_replace('/(d+)/', '<strong>$1</strong>', $diff);
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

    public function getMainCategoryIdAttribute()
    {
        return $this->category->parent->id ?? $this->category->id;
    }
    public function getMainCategoryNameAttribute()
    {
        return $this->category->parent->name ?? $this->category->name;

    }

    public function getIsReportedAttribute()
    {
        return $this->reported()->where('is_read', false)->first()
            ?
            $this->reported()->where('is_read', false)->first()->user()->first()
            :
            false;
    }

    public function getIsNegotiableAttribute()
    {
        return $this->negotiable ? 'قابل للتفاوض' : 'غير قابل للتفاوض';
    }

}
