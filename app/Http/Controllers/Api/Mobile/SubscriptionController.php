<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\{
    SubscriptionUpdateRequest,
    SubscriptionRequest
};
use App\Models\Plan;
use Illuminate\Support\Facades\{
    Auth,DB
};
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use NunoMaduro\Collision\Adapters\Phpunit\Subscribers\Subscriber;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        return $this->showResponse($user->subscriptions()->with(['plan'])->get(),'done successfully...!');
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
        try{
        $user=Auth::user();
        $user->subscriptions()->create(['plan_id'=>$request->plan_id]);
        DB::commit();
        return $this->showMessage('subscription done successfully.....!');
        }
        catch(Exception $e){
            return $this->showError($e,'something goes wrong....!');

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
        try{
        $subscription=Subscription::find($id)->first();
         $plan = $subscription->plan;
        $subscription->update([
            'status'=>$request->status,
            'starts_at'=>Carbon::now(),
            'ends_at'=>Carbon::now()->addDays(intval($plan->duration))
        ]);
        DB::commit();
        return $this->showResponse($subscription,'subscription updated successfully...!');
    }
    catch(Exception $e){
        return $this->showError($e,'something goes wrong.....!');

    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
        $user=Auth::user();
        $subscription= $user->subscriptions()->find($id)->first();
        $subscription->delete();
        DB::commit();
        return $this->showResponse($subscription,'subscription delete successfully....!');
        }catch(Exception $e){
            return $this->showError($e,'something goes wrong....!');

        }


    }
}
