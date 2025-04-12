<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\Dashboard\StatisticService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    use ResponseTrait;
    public function __construct(public StatisticService $service)
    {
    }

    public function statistics(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required',
                'first_date' => 'required',
                'second_date' => 'required',
            ]);
            $data = $this->service->statistics($request);
            return $this->showResponse($data, 'Daily statistics retrieved !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while retrieving daily statistics !!!');
        }
    }



}
