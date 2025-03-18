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
        DB::beginTransaction();
        try {
            $categoryId = $request->query('category_id');

            if ($categoryId) {
                $category = Category::with('children')->findOrFail($categoryId);
                $ads = Advertisement::whereIn('category_id', $category->children()->pluck('id')->push($category->id))
                    ->with(['images'])
                    ->orderByRaw('is_special DESC, created_at DESC')
                    ->where('status', 'active')
                    ->paginate(10);

            } else {
                $ads = Advertisement::with(['images'])
                    ->orderByRaw('is_special DESC, created_at DESC')
                    ->where('status', 'active')
                    ->paginate(10);
            }
            DB::commit();
            return $this->showResponse($ads, 'done successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong...!');

        }
    }

    public function indexWithFilter(Request $request)
    {
        $ads = $this->service->indexWithFilter();
        return $this->showResponse($ads);
    }
}
