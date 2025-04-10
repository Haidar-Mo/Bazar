<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class IsEndedPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {

            $subscription = $user->subscriptions()->where('status', 'running')->first();

            if ( !$subscription||$subscription->number_of_ads <= 0 ) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك الاشتراك بباقة جديدة لتتمكن من نشر الإعلانات.'
                ], 422);
            }
        }

        return $next($request);
    }

}
