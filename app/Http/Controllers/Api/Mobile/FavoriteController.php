<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Http\Requests\Mobile\{
    FavoriteRequest,
    UpdateFavoriteRequest
};
use App\Models\{
    FavoriteList
};
use App\Traits\{
    ResponseTrait
};
use Illuminate\Support\Facades\{DB, Auth};
use Exception;

class FavoriteController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $favoriteListId = $user->favorite()->where('name', 'All')->firstOrFail()->id;

        $ads = Advertisement::select('advertisements.*')
        ->join('favorite_list_items', 'advertisements.id', '=', 'favorite_list_items.advertisement_id')
        ->where('favorite_list_items.favorite_list_id', $favoriteListId)
        ->orderByRaw('advertisements.is_special DESC, favorite_list_items.created_at DESC')
        ->paginate(2);

        $ads->getCollection()->map(function ($item) {
            return $this->formatAdvertisement($item);
        });

        return $this->showResponse($ads, 'Favorite items retrieved successfully');
    }



    public function store(FavoriteRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            DB::commit();
            return $this->showResponse($user->favorite()->create(['name' => $request->name]), 'create favorite list successfully....!');

        } catch (Exception $e) {
            return $this->showError($e, 'something goes wrong....!');
        }
    }




    public function show(string $id)
    {
        $user = Auth::user();
        $favoriteList = FavoriteList::with([
            'items.ads' => function ($query) {
                $query->with(['images', 'attributes', 'category']);
            }
        ])
            ->where('user_id', $user->id)
            ->findOrFail($id);
        $paginatedItems = $favoriteList->items()
            ->paginate(10);


        return response()->json([
            'pagination' => $paginatedItems->toArray(),
            'favorite_list' => [
                'id' => $favoriteList->id,
                'name' => $favoriteList->name,
                'items' => $paginatedItems->map(function ($item) {
                    return [
                        'advertisement' => $this->formatAdvertisement($item->ads)
                    ];
                }),
            ],

        ]);
    }


    protected function formatAdvertisement($ad)
    {
        // $priceAttribute = collect($ad->attributes)->firstWhere('price');
        return [
            'id' => $ad->id,
            'user_id' => $ad->user_id,
            'city_id' => $ad->city_id,
            'category_id' => $ad->category_id,
            'title' => $ad->title,
            'type' => $ad->type,
            'currency_type' => $ad->currency_type,
            'negotiable' => $ad->negotiable,
            'status' => $ad->status,
            'expiry_date' => $ad->expiry_date,
            'price' => $ad->price ?: 0,
            'is_special' => $ad->is_special,
            'images' => $ad->images,
            'views' => $ad->views,
            'created_from' => $ad->created_from,
            'is_favorite' => $ad->is_favorite,
            'main_category_id' => $ad->main_category_id,
            'main_category_name' => $ad->main_category_name,
            'city_name' => $ad->city_name,
            'category_name' => $ad->category_name,
            'user_name' => $ad->user_name,

        ];
    }





    public function update(UpdateFavoriteRequest $request, string $id)
    {
        $favoritelist = FavoriteList::find($id)->first();
        $favoritelist->update([
            'name' => $request->name
        ]);
        return $this->showMessage('updated successfully');
    }


    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $favoritelist = FavoriteList::find($id)->first();
            $favoritelist->delete();
            DB::commit();
            return $this->showMessage('deleted successfully....!');
        } catch (Exception $e) {
            DB::rollback();
            return $this->showError($e, 'something goes wrong....!');
        }


    }
}
