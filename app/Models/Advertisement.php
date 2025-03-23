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
        'images',
        'views',
        'created_from',
        'is_favorite',
        'main_category_id',
        'main_category_name',
        'user_name',
        'city_name',
        'category_name',

    ];

    protected $hidden = [
        'deleted_at',
        'category'
    ];

    public function casts()
    {
        return [
            'is_special' => 'boolean',
            'negotiable'=>'boolean',
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

    public function report(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    //! Accessories

    public function getUserNameAttribute()
    {
        return $this->user()->first()->first_name . $this->user()->first()->last_name;
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
        return $this->attributes()->get()->groupBy('title')->map(function ($attributes) {
            return $attributes->pluck('value', 'name');
        });
    }

    public function getCreatedFromAttribute()
    {

        Carbon::setLocale('ar');
        $diff = $this->created_at->locale('ar')->diffForHumans();
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
        return $this->report()->where('status', 'pending')->first() ? true : false;
    }

}
