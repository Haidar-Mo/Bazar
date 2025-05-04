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
            $plan = Plan::FindOrFail($request->plan_id);
            $user->subscriptions()->create([
                'plan_id' => $plan->id,
            ]);
            $admins = User::role(['admin', 'supervisor'], 'api')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NotificationSubscription("قام ( {$user->name} )بإرسال طلب اشتراك بالباقة ( {$plan->name} )"));
            }

            DB::commit();
            return $this->showMessage('تم ارسال طلب الاشتراك...');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e);
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
            $subscription->update(['status' => 'ended']);
            DB::commit();
            return $this->showResponse($subscription, 'تم إنهاء الاشتراك بنجاح');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong....!');
        }
    }
}
