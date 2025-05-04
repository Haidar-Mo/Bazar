<?php

namespace App\Services\Dashboard;

use App\Models\Advertisement;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class StatisticService.
 */
class StatisticService
{

    /**
     * Calculate the statistics
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function statistics(Request $request)
    {
        $date_1 = $request->input('first_date');
        $date_2 = $request->input('second_date');
        $type = $request->input('type', 'daily');

        $groupByFormat = match ($type) {
            'daily' => 'DATE(created_at)',
            'weekly' => 'WEEK(created_at)',
            'monthly' => 'MONTH(created_at)',
            default => 'DATE(created_at)',
        };



        //- Group ads by time range
        $all_ads = Advertisement::whereBetween('created_at', [$date_1, $date_2])
            ->select(DB::raw("$groupByFormat as period"), DB::raw('count(*) as count'))
            ->groupBy('period')
            ->get();

        $ads_count = $all_ads->map(fn($item) => [
            'date' => strval($item->period),
            'total_ads' => $item->count,
        ]);


        //- Group subscriptions by time range
        $all_subs = Subscription::whereBetween('created_at', [$date_1, $date_2])
            ->select(DB::raw("$groupByFormat as period"), DB::raw('count(*) as count'))
            ->groupBy('period')
            ->get();

        $subs_count = $all_subs->map(fn($item) => [
            'date' => strval($item->period),
            'total_subs' => $item->count,
        ]);


        //- Calculate verified/unverified ratio
        $verified_users = User::where('is_verified', 1)->count();
        $unverified_users = User::where('is_verified', 0)->count();
        $total = $verified_users + $unverified_users;

        $users_ratio = $total > 0 ? [
            'verified' => $verified_users * 100 / $total,
            'unverified' => $unverified_users * 100 / $total,
        ] : [
            'verified' => 0,
            'unverified' => 0,
        ];


        //- Get most viewed activated ads
        $most_viewed_ads = Advertisement::where('status', 'active')
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->limit(20)
            ->get(['id', 'title', 'created_at', 'main_category_name', 'category_name']);



        //- Financial statistic
        $subscriptions = Subscription::where('status', '!=', 'pending')->whereBetween('created_at', [$date_1, $date_2])
            ->groupBy('plan_id')
            ->selectRaw('plan_id, COUNT(*) as subscription_count, SUM(afford_price) as total_benefits')
            ->get();

        $total_benefits = $subscriptions->sum('total_benefits');

        return [
            'ads_count' => $ads_count,
            'subs_count' => $subs_count,
            'users_ratio' => $users_ratio,
            'most_viewed_ads' => $most_viewed_ads,
            'subscriptions' => $subscriptions,
            'total_benefits' => $total_benefits,

        ];
    }

}
