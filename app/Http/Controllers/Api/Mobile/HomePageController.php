<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Advertisement,
    User,
    Category
};
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\{Auth,DB};
use App\Http\Resources\AdvertisementResource;
class HomePageController extends Controller
{
    use ResponseTrait;



    public function index(Request $request)
    {
        DB::beginTransaction();
        try{
        $categoryId = $request->query('categoryId');

        if ($categoryId) {
            $category = Category::with('children')->findOrFail($categoryId);
            $ads = Advertisement::whereIn('category_id', $category->children()->pluck('id')->push($category->id))
                ->with(['images'])
                ->orderByRaw('is_special DESC, created_at DESC')
                ->where('status','active')
                ->paginate(10);
        } else {
            $ads = Advertisement::with(['images'])
                ->orderByRaw('is_special DESC, created_at DESC')
                ->where('status','active')
                ->paginate(10);
        }
        DB::commit();
        return $this->showResponse(AdvertisementResource::collection($ads),'done successfully...!') ;
    }catch(Exception $e){
        DB::rollBack();
        return $this->showError($e,'something goes wrong...!');

    }
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
