<?php

namespace App\Observers;

use App\Models\Plan;
use App\Models\User;

class StarterPlan
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $plan = Plan::where('name', 'starter')->first();
        if ($plan) {
            $user->subscriptions()->create([
                'plan_id' => $plan->id,
                'number_of_ads' => $plan->size,
                'start_at' => now(),
                'end_at' => now()->addDays($plan->duration),
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
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
