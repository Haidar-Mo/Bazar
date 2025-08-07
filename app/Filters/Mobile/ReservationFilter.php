<?php

namespace App\Filters\Mobile;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class ReservationFilter extends BaseFilter
{
    public function apply(Builder $query)
    {

        if ($this->request->filled("status")) {
            $query->where("status", $this->request->get("status"));
        }

        if ($this->request->filled("title")) {
            $query->whereHas('advertisement', function ($advertisement) {
                $advertisement->where("title", "like", "%" . $this->request->get("title") . "%");
            });
        }
        return $query;

    }
}
