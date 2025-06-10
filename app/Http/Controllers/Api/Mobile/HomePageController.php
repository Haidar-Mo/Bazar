<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Mobile\AdvertisementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Advertisement,
    Category
};
use App\Traits\ResponseTrait;
use Exception;

class HomePageController extends Controller
{
    use ResponseTrait;


    public function __construct(protected AdvertisementService $service)
    {
    }

    public function index(Request $request)
    {
        try {
            $ads = $this->service->index()
                ->with(['images'])
                ->orderByRaw('is_special DESC, created_at DESC')
                ->where('status', 'active')
                ->paginate(10);
            return $this->showResponse($ads, 'done successfully...!');
        } catch (Exception $e) {
            return $this->showError($e, 'something goes wrong...!');
        }
    }
}
