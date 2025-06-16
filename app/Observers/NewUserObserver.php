<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Plan;
use App\Models\User;
use App\Traits\FirebaseNotificationTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewUserObserver
{
    use FirebaseNotificationTrait;
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $plan = Plan::where('name', 'الباقة المجانية')->first();
        if ($plan) {
            Log::info('subscription to free plan ');
            $user->subscriptions()->create([
                'plan_id' => $plan->id,
                'number_of_ads' => $plan->size,
                'status' => 'running',
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addDays(intval($plan->duration)),
            ]);
        }
        $categories = Category::whereNull('parent_id')->get();
        foreach ($categories as $category) {
            Log::info('subscription to notification settings ');
            $user->notificationSettings()->create([
                'category_id' => $category->id,
                'is_active' => true,
            ]);
            if ($user->device_token) {

                $this->subscribeToTopic($user->device_token, $category->id);
            }

        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {

    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
