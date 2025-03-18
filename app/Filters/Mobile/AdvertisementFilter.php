<?php

namespace App\Filters\Mobile;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementFilter extends BaseFilter
{

    public function apply(Builder $query)
    {

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

        if ($this->request->filled('min_area') && $this->request->filled('max_area')) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'area')->whereBetween('value', [$this->request->get('min_area'), $this->request->get('max_area')]);
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
