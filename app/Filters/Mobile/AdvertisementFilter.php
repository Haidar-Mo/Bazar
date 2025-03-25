<?php

namespace App\Filters\Mobile;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementFilter extends BaseFilter
{

    public function apply(Builder $query)
    {

        //! Common filters

        if ($this->request->filled('city_id')) {
            $query->where('city_id', $this->request->city_id);

        }
        if ($this->request->filled('category_id')) {
            $query->where('category_id', $this->request->category_id);
        }

        if ($this->request->filled('min_price') && $this->request->filled('max_price')) {
            $query->whereBetween('price', [$this->request->min_price, $this->request->max_price]);
        }

        if ($this->request->filled('type')) {
            $query->where('type', $this->request->type);
        }

        if ($this->request->filled('currency_type')) {
            $query->where('currency_type', $this->request->currency_type);
        }

        if ($this->request->filled('negotiable')) {
            $query->where('negotiable', $this->request->negotiable);
        }

        //- PROPRIETIES

        if ($this->request->filled('min_area') && $this->request->filled('max_area')) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'size')->whereBetween('value', [$this->request->get('min_area'), $this->request->get('max_area')]);
            });
        }

        if ($this->request->filled('real_estate_type')) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'real_estate_type')->where('value', $this->request->real_estate_type);
            });
        }

        if ($this->request->filled('lease_type')) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'lease_type')->where('value', $this->request->lease_type);
            });
        }

        if ($this->request->filled('purpose')) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'purpose')->where('value', $this->request->purpose);
            });
        }

        if ($this->request->filled('bedrooms')) {
            $attributes = $this->request->get('attributes');

            if ($this->request->bedrooms === '+') {
                $query->whereHas('attributes', function ($query) use ($attributes) {
                    $query->where('name', 'bedrooms')->where('value', '>', (int) 5);
                });
            } else {
                $query->whereHas('attributes', function ($query) use ($attributes) {
                    $query->where('name', 'bedrooms')->where('value', (int) $this->request->bedrooms);
                });
            }
        }

        if ($this->request->filled('bathrooms')) {
            $attributes = $this->request->get('attributes');

            if ($this->request->bathrooms === '+') {
                $query->whereHas('attributes', function ($query) use ($attributes) {
                    $query->where('name', 'bathrooms')->where('value', '>', (int) 5);
                });
            } else {
                $query->whereHas('attributes', function ($query) use ($attributes) {
                    $query->where('name', 'bathrooms')->where('value', (int) $this->request->bathrooms);
                });
            }
        }

        if ($this->request->filled('furnishing')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'furnishing')->where('value', $this->request->furnishing);
            });
        }

        //- Vehicle

        //- Jobs

        //- electronics
/*
        if ($this->request->filled('category')) {
            $query->where('category_id', $this->request->category);
        }
        
        if ($this->request->filled('condition')) {
            $query->where('condition', $this->request->condition);
        }
        
        if ($this->request->filled('storage_capacity')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'storage_capacity')->where('value', $this->request->storage_capacity);
            });
        }
        
        if ($this->request->filled('color')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'color')->where('value', $this->request->color);
            });
        }
        
        if ($this->request->filled('price_min') && $this->request->filled('price_max')) {
            $query->whereBetween('price', [$this->request->price_min, $this->request->price_max]);
        } elseif ($this->request->filled('price_min')) {
            $query->where('price', '>=', $this->request->price_min);
        } elseif ($this->request->filled('price_max')) {
            $query->where('price', '<=', $this->request->price_max);
        }
        
        if ($this->request->filled('warranty')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'warranty')->where('value', $this->request->warranty);
            });
        }
        
        if ($this->request->filled('phone_age')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'phone_age')->where('value', $this->request->phone_age);
            });
        }
        
        if ($this->request->filled('screen_size')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'screen_size')->where('value', $this->request->screen_size);
            });
        }
        
        if ($this->request->filled('ram')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'ram')->where('value', $this->request->ram);
            });
        }
        
        if ($this->request->filled('features')) {
            $features = $this->request->features;
            $query->whereHas('attributes', function ($query) use ($features) {
                $query->where('name', 'features')->whereIn('value', $features);
            });
        }
*/

















        if ($this->request->filled('attributes') && is_array($this->request->get('attributes'))) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                foreach ($attributes as $key => $value) {
                    $query->where('name', $key)->where('value', $value);
                }
            });
        }



        return $query;

    }
}
