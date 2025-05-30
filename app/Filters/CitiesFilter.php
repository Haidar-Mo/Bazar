<?php

namespace App\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class CitiesFilter extends BaseFilter
{
    public function apply(Builder $query)
    {
        if ($this->request->filled('parent')) {
            if ($this->request->parent == 'SY') {
                $query->whereIn('name', [
                    "دمشق",
                    "حمص",
                    "حلب",
                    "اللاذقية",
                    "حماة",
                    "طرطوس",
                    "الرقة",
                    "تدمر",
                    "السويداء",
                    "درعا",
                    "إدلب",
                    "القنيطرة",
                    "الحسكة",
                    "دير الزور",
                ])->get();
            } elseif ($this->request->parent == 'GR') {
                $query->whereIn('name', [
                    "Berlin",
                    "Hamburg",
                    "Munich",
                    "Cologne",
                    "Frankfurt",
                    "Stuttgart",
                    "Düsseldorf",
                    "Dortmund",
                    "Essen",
                    "Leipzig",
                    "Bremen",
                    "Dresden",
                    "Hanover",
                    "Nuremberg",
                    "Duisburg",
                    "Bochum",
                    "Wuppertal",
                    "Bielefeld",
                    "Bonn",
                    "Münster",
                ])->get();
            }

        }

        return $query;
    }
}