<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Traits\ResponseTrait;
use App\Http\Requests\{
    PlanUpdateRequest,
    PlanRequest
};
use Exception;
use Illuminate\Support\Facades\{
    DB,
    Auth
};
class PlanController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showResponse(Plan::where('name', '!=', 'starter')->get(), 'done successfully.....!');
    }


    /**
     * Display the specified resource.
     */
    /* public function show(string $id)
     {
         return $this->showResponse(Plan::FindOrFail($id)->first(),'done successfully...!');
     }*/

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    /*public function update(PlanUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try{
        $plan=Plan::find($id)->first();
        $plan->update($request->all());
        DB::commit();
        return $this->showResponse($plan,'plan updated successfully...!');
        }
        catch(Exception $e){
            DB::rollBack();
            return $this->showError($e,'something goes wrong....!');
        }

    }*/

    /**
     * Remove the specified resource from storage.
     */
    /*public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
        $plan=Plan::find($id)->first();
        $plan->delete();
        DB::commit();
        return $this->showResponse($plan,'plan deleted successfully....!');
        }
        catch(Exception $e){
            return $this->showError($e,'something goes wrong....!');

        }
    }*/
}
