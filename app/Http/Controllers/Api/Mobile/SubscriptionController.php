<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Http\Requests\{
    SubscriptionUpdateRequest,
    SubscriptionRequest
};
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use App\Notifications\Dasboard\NotificationSubscription;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return $this->showResponse($user->subscriptions()->where('status', 'running')->with(['plan'])->get(), 'done successfully...!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user->subscriptions()->where('status', 'running')->exists()) {
                return $this->showMessage('لا يمكنك الاشتراك لوجود باقة مفعلة مسبقا', 422);
            }
            $user->subscriptions()->create(['plan_id' => $request->plan_id]);
            $admins = User::role(['admin', 'supervisour'], 'api')->get();
            $plan = Plan::FindOrFail($request->plan_id);
            foreach ($admins as $admin) {
                $admin->notify(new NotificationSubscription("قام ( {$user->name} )بالاشتراك بالباقة ( {$plan->name} )"));
            }


            DB::commit();
            return $this->showMessage('تم ارسال طلب الاشتراك...');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubscriptionUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $subscription = Subscription::find($id);
            $user = Auth::user();
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
                'number_of_ads' => $subscription->plan->size,
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $subscription = $user->subscriptions()->find($id)->first();
            $subscription->delete();
            DB::commit();
            return $this->showResponse($subscription, 'subscription delete successfully....!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong....!');
        }
    }
}
