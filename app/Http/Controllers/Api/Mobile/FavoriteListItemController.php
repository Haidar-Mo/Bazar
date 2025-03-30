<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    FavoriteList,
    FavoriteListItem
};
use App\Traits\{
    HasFiles,
    ResponseTrait
};
use App\Http\Requests\Mobile\FavoriteItemRequest;
use Exception;
use Illuminate\Support\Facades\{DB, Auth};

use function Laravel\Prompts\select;

class FavoriteListItemController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(FavoriteItemRequest $request)
    {

        $user = Auth::user();
        $favoriteList = $user->favorite()->first();
        if (!$favoriteList)
            $favoriteList = $user->favorite()->create(['name' => 'ALL']);
        $ListId = $favoriteList->id;
        DB::beginTransaction();
        try {
            FavoriteListItem::firstOrCreate([
                'favorite_list_id' => $ListId,
                'advertisement_id' => $request->ads_id,
            ]);
            /*foreach ($request->lists as $listId) {
                FavoriteListItem::firstOrCreate([
                    'favorite_list_id' => $listId,
                    'advertisement_id' => $request->ads_id,
                ]);
            }*/
            DB::commit();
            return $this->showMessage('added item to list successfully....!');
        } catch (Exception $e) {
            return $this->showError($e, 'Something goes wrong....!');

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = Auth::user();
        $favoriteList = $user->favorite()->first();
        $ListId = $favoriteList->id;
        DB::beginTransaction();
        try {

            $favoriteListItem = FavoriteListItem::where('advertisement_id', $id)
                ->where('favorite_list_id', $ListId)
                ->first();
            $favoriteListItem->delete();
            DB::commit();

            return $this->showMessage('ad delete from favorite successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong');
        }
    }


}
