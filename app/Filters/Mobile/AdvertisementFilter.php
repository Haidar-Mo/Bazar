<?php

namespace App\Filters\Mobile;

use App\Filters\BaseFilter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementFilter extends BaseFilter
{
    protected $request;
    protected $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        if ($this->request->filled('attributes')) {
            $attributes = $this->request->attributes;

            $this->query->whereHas('attributes', function ($query) use ($attributes) {
                foreach ($attributes as $name => $value) {
                    $query->where('name', $name)->where('value', $value);
                }
            });
        }

        if ($this->request->filled('city_id')) {
            $this->query->where('city_id', $this->request->city_id);

        }
        if ($this->request->filled('category_id')) {
            $this->query->where('category_id', $this->request->category_id);
        }

        if ($this->request->filled('min_price') && $this->request->filled('max_price')) {
            $this->query->whereBetween('price', [$this->request->min_price, $this->request->max_price]);
        }

        return $this->query;
    }
}
