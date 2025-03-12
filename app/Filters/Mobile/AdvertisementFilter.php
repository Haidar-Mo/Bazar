<?php

namespace App\Filters\Mobile;

use App\Filters\BaseFilter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementFilter extends BaseFilter
{
    //return $query->where();

    /*
        //! Category Filter
        public function category_id(Builder $query)
        {
            return $query->where('category_id', $this->request->category_id);
        }


        //! Price Filter
        public function price(Builder $query)
        {
            return $query->whereBetween('price', [$this->request->min_price, $this->request->max_price]);
        }


        //! Name Filter
        public function name(Builder $query)
        {
            return $query->where('name', 'like', '%' . $this->request->name . '%');
        }

        public function attributes(Builder $query)
        {
            $attributes = $this->request->attributes;
            return $query->whereHas('attributes', function ($query) use ($attributes) {
                foreach ($attributes as $name => $value) {
                    $query->where('name', $name)->where('value', $value);
                }
            });
        }
    */
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

        if ($this->request->filled('min_area') && $this->request->filled('max_area')) {
            $attributes = $this->request->get('attributes');

            $query->whereHas('attributes', function ($query) use ($attributes) {
                $query->where('name', 'area')->whereBetween('value', [$this->request->get('min_area'), $this->request->get('max_area')]);
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
