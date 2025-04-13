<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Http\Requests\{
    SubscriptionUpdateRequest,

};
use App\Models\User;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $subscriptions = Subscription::where('status', $request->status)->get();
        $simpleSubscriptions = $subscriptions->map(function ($subscription) {
            return $subscription->toSimpleArray();
        });

        return $this->showResponse($simpleSubscriptions, 'done successfully...!');
    }


    public function show(string $id)
    {
        $subscription=Subscription::with(['plan','user'])->findOrFail($id);
        return $this->showResponse($subscription,'done successfully...!');
    }


    public function update(SubscriptionUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $subscription = Subscription::find($id);
            $user =User::find($subscription->user_id);
            $activeSubscription = $user->subscriptions()
                ->where('status', 'running')
                ->where('id', '!=', $id)
                ->first();
            if ($activeSubscription) {
                $activeSubscription->update(['status' => 'ended']);
            }

            $plan = $subscription->plan;
            $subscription->update([
                'status' => $request->status,
                'number_of_ads'=>$subscription->plan->size,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addDays(intval($plan->duration))
            ]);

            DB::commit();
            return $this->showResponse($subscription, 'subscription updated successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong.....!');
        }
    }

}
