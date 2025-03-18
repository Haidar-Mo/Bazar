<?php

namespace App\Filters\Dashboard;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class AdvertisementFilter extends BaseFilter
{

    public function apply(Builder $query)
    {
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('title')) {
            $query->where('title', '%' . $this->request->title . '%');
        }

        if ($this->request->filled('category_id')) {
            $query->where('category_id', $this->request->category_id);
        }

        if ($this->request->filled('time')) {
            switch ($this->request->time) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;

                case 'last_7_days':
                    $query->whereBetween('created_at', [now()->subDays(7), now()]);
                    break;

                case 'last_30_days':
                    $query->whereBetween('created_at', [now()->subDays(30), now()]);
                    break;

                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;

                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;

                case 'this_year':
                    $query->whereYear('created_at', now()->year);
                    break;

                case 'last_year':
                    $query->whereYear('created_at', now()->subYear()->year);
                    break;

                case 'custom':
                    if ($this->request->has(['start_date', 'end_date'])) {
                        $query->whereBetween('created_at', [
                            Carbon::parse($this->request->start_date),
                            Carbon::parse($this->request->end_date)->endOfDay(),
                        ]);
                    }
                    break;
            }
        }
        return $query;

    }
}
