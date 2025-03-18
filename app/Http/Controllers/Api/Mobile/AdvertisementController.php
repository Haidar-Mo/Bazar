<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\{
    UpdateAdvertisementRequest,
    AdvertisementCreateRequest
};
use App\Models\Advertisement;
use App\Traits\ResponseTrait;
use App\Services\Mobile\AdvertisementService;
use Exception;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class AdvertisementController extends Controller
{
    use ResponseTrait;


    public function __construct(private AdvertisementService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        if ($request->has('status') && $request->status != '') {

            $ads = $user->ads()
                ->where('status', $request->status)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {

            $ads = $user->ads()
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return $this->showResponse($ads, 'done');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvertisementCreateRequest $request)
    {
        $user = $request->user();
        // DB::beginTransaction();

        $ads = $this->service->create($request, $user);
        $ads->append(['images', 'attributes']);
        // DB::commit();
        return $this->showResponse($ads, 'create ads successfully ...!');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = Auth::user();
        DB::beginTransaction();
        try {

            $ad = Advertisement::with('user')
                ->where('id', $id)
                ->firstOrFail();
            $user->views()->firstOrCreate(
                ['advertisement_id' => $id],
                ['advertisement_id' => $id, 'user_id' => $user->id]
            );
            DB::commit();
            return $this->showResponse($ad->append('attributes'), 'done successfully....!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong....!');

        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertisementRequest $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $ad = Advertisement::find($id);
            $ad->delete();
            DB::commit();
            return $this->showMessage('ad deleted successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong....!');

        }
    }


    public function indexWithFilter(Request $request)
    {
        try {
            $data = $this->service->indexWithFilter();
            return $this->showResponse($data, 'filtered Ads retrieved successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while filtering the advertisements', 500);
        }

    }
}
