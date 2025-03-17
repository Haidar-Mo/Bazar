<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Http\Requests\PlanUpdateRequest;
use App\Models\Plan;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class PlanController extends Controller
{
    use ResponseTrait;

    /**
     * Display list of all subscription plans
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->showResponse(Plan::all(), 'Plans retrieved successfully !!', 200);
    }

    /**
     * Create new subscription plan
     * @param \App\Http\Requests\PlanRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PlanRequest $request)
    {
        DB::beginTransaction();
        try {
            $plan = Plan::create($request->all());
            DB::commit();
            return $this->showResponse($plan, 'Subscription Plan created successfully !!');
        } catch (Exception $e) {
            DB::rollback();
            return $this->showError($e, 'An error occur while creating new subscription plan !!');
        }

    }
    /**
     * Show specific subscription plan
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        
        return $this->showResponse(Plan::where('id',$id)->get(), 'plan retrieved successfully !!');
    }


    /**
     * Update specific subscription plan
     * @param \App\Http\Requests\PlanUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PlanUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $plan = Plan::find($id)->first();
            $plan->update($request->all());
            DB::commit();
            return $this->showResponse($plan, 'plan updated successfully !!');
        } catch (Exception $e) {
            DB::rollback();
            return $this->showError($e, 'An error occur while updating new subscription plan !!');
        }

    }

    /**
     * Remove specific subscription plan
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $plan = Plan::find($id)->first();
            $plan->delete();
            DB::commit();
            return $this->showMessage('plan deleted successfully !!',204);
        } catch (Exception $e) {
            DB::rollback();
            return $this->showError($e, 'An error occur while deleting new subscription plan !!');

        }
    }
}
