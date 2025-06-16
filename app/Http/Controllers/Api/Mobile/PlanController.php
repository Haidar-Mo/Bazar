<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Traits\ResponseTrait;

class PlanController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $plans = Plan::where('name', '!=', 'الباقة المجانية')->get();
            return $this->showResponse($plans, 'done successfully.....!');

        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

}
