<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mobile\{
    FavoriteRequest,
    UpdateFavoriteRequest
};
use App\Models\{
    FavoriteList,
    FavoriteListItem
};
use App\Traits\{
    HasFiles,
    ResponseTrait
};
use Exception;
use Illuminate\Support\Facades\{DB,Auth};
class FavoriteController extends Controller
{
    use ResponseTrait;
 /*$user=Auth::user();
        $favoritelist=$user->favorite()->get();
        return $this->showResponse($favoritelist,'done');*/
        public function index()
        {
            $user = Auth::user();
            $favoriteLists = $user->favorite()->get();

            $formattedFavoriteLists = $favoriteLists->map(function ($favoriteList) {
                $itemsPaginator = $favoriteList->items()
                    ->with(['ads' => function ($query) {
                        $query->with(['images', 'attributes', 'category']);
                    }])
                    ->join('advertisements', 'favorite_list_items.advertisement_id', '=', 'advertisements.id')
                    ->select('favorite_list_items.*')
                    ->orderByRaw('advertisements.is_special DESC, favorite_list_items.created_at DESC') // ترتيب حسب is_special ثم created_at
                    ->paginate(10);

                $formattedItems = $itemsPaginator->getCollection()->map(function ($item) {
                    return $this->formatAdvertisement($item->ads);
                });

                $itemsPaginator->setCollection($formattedItems);

                return [
                    'id' => $favoriteList->id,
                    'name' => $favoriteList->name,
                    'items' => $itemsPaginator,
                ];
            });

            return response()->json([
                'favorite_list' => $formattedFavoriteLists,
            ]);
        }






    public function store(FavoriteRequest $request)
    {
        DB::beginTransaction();
        try{
        $user=Auth::user();
        DB::commit();
        return $this->showResponse($user->favorite()->create(['name'=>$request->name]),'create favorite list successfully....!');

        }catch(Exception $e){
            return $this->showError($e,'something goes wrong....!');
        }
    }




    public function show(string $id)
    {
        $user=Auth::user();
         $favoriteList = FavoriteList::with(['items.ads' => function($query) {
            $query->with(['images', 'attributes','category']);
        }])
        ->where('user_id', $user->id)
        ->findOrFail($id);
        $paginatedItems = $favoriteList->items()
        ->paginate(10);


    return response()->json([
        'pagination' => $paginatedItems->toArray(),
        'favorite_list' => [
            'id' => $favoriteList->id,
            'name' => $favoriteList->name,
            'items' => $paginatedItems->map(function($item) {
                return [
                    'advertisement' => $this->formatAdvertisement($item->ads)
                ];
            }),
        ],

    ]);
}


protected function formatAdvertisement($ad)
{
    $priceAttribute = collect($ad->attributes)->firstWhere('price');
    return [
        'id' => $ad->id,
        'images' => $ad->images,
        'price' => $priceAttribute ? $priceAttribute['price'] : 0,
        'is_special'=>$ad->is_special,
        'created_at'=>$ad->created_from,
        'category' => $ad->category,

    ];
}





    public function update(UpdateFavoriteRequest $request, string $id)
    {
        $favoritelist=FavoriteList::find($id)->first();
        $favoritelist->update([
            'name'=>$request->name
        ]);
        return $this->showMessage('updated successfully');
    }


    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
        $favoritelist=FavoriteList::find($id)->first();
        $favoritelist->delete();
        DB::commit();
        return $this->showMessage('deleted successfully....!');
        }
        catch(Exception $e){
            DB::rollback();
            return $this->showError($e,'something goes wrong....!');
        }


    }
}
