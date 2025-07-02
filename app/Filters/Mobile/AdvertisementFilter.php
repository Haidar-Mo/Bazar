<?php

namespace App\Filters\Mobile;

use App\Filters\BaseFilter;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementFilter extends BaseFilter
{
    public function apply(Builder $query)
    {

        //: COMMON FILTERS

        if ($this->request->filled('word')) {
            $searchTerm = "%" . $this->request->word . "%";

            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('first_name', 'LIKE', $searchTerm)
                            ->orWhere('last_name', 'LIKE', $searchTerm);
                    });
            });
        }
        if ($this->request->filled('city_id')) {
            $query->where('city_id', $this->request->city_id);
        }
        if ($this->request->filled('category_id')) {
            $category = Category::findOrFail($this->request->category_id);
            $query->whereIn('category_id', array_merge([$this->request->category_id], $category->children()->get()->pluck('id')->toArray()));
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

        //: PROPRIETIES

        if ($this->request->filled('min_area') && $this->request->filled('max_area')) {

            $query->whereHas('attributes', function ($query) {
                $query->where('name', 'size')->whereBetween('value', [(int) $this->request->get('min_area'), (int) $this->request->get('max_area')]);
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

        if ($this->request->filled('land_use')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'land_use')->where('value', $this->request->land_use);
            });
        }
        if ($this->request->filled('soil_type')) {
            $attributes = $this->request->get('attributes');
            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'soil_type')->where('value', $this->request->soil_type);
            });
        }

        //: electronics

        if ($this->request->filled('duration_of_use')) {
            $duration_of_use = $this->request->duration_of_use;
            $query->whereHas('attributes', function ($query) use ($duration_of_use) {
                $query->where('name', 'duration_of_use')->where('value', $duration_of_use);
            });
        }

        if ($this->request->filled('version')) {
            $version = $this->request->version;
            $query->whereHas('attributes', function ($query) use ($version) {
                $query->where('name', 'version')->where('value', $this->request->version);
            });
        }

        if ($this->request->filled('storage')) {
            $storage = $this->request->storage;
            $query->whereHas('attributes', function ($query) use ($storage) {
                $query->where('name', 'storage')->where('value', $this->request->storage);
            });
        }



        //: CLOTHES AND FASHION

        if ($this->request->filled('color')) {
            $color = $this->request->color;
            $query->whereHas('attributes', function ($query) use ($color) {
                $query->where('name', 'color')->where('value', $color);
            });
        }

        if ($this->request->filled('fabric')) {
            $fabric = $this->request->fabric;
            $query->whereHas('attributes', function ($query) use ($fabric) {
                $query->where('name', 'fabric')->where('value', $fabric);
            });
        }



        //-FURNITURE

        if ($this->request->filled('light_color')) {
            $light_color = $this->request->light_color;
            $query->whereHas('attributes', function ($query) use ($light_color) {
                $query->where('name', 'light_color')->where('value', $light_color);
            });
        }



        // if ($this->request->filled('attributes') && is_array($this->request->get('attributes'))) {
        //     $attributes = $this->request->get('attributes');

        //     $query->whereHas('attributes', function ($query) use ($attributes) {
        //         foreach ($attributes as $key => $value) {
        //             $query->where('name', $key)->where('value', $value);
        //         }
        //     });
        // }



        return $query;

    }
}
