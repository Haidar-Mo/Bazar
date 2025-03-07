<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\{
    UpdateAdvertmentRequest,
    AdvertisementCreateRequest

};
use App\Models\Advertisement;
use App\Traits\{
    HasFiles,
    ResponseTrait
};
use App\Services\Mobile\AdvertisementService;
use Exception;
use Illuminate\Support\Facades\Auth;

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
            $ads = $user->ads()->with('images')->where('status', $request->status)->orderBy('created_at', 'desc')->paginate(10);
        } else {

            $ads = $user->ads()->with('images')->orderBy('created_at', 'desc')->paginate(10);
        }
        return $this->showResponse($ads, 'done');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvertisementCreateRequest $request)
    {
        $user = Auth::user();
        try {
            $ads = $this->service->create($request, $user);
            return $this->showResponse($ads, 'create ads successfully ...!');
        }catch(Exception $e){
            return $this->showError($e,' SomeThing goes wrong....! ');

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $ad=Advertisement::find($id)->with(['category','city'])->first();
        return $this->showResponse($ad->append('attributes'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertmentRequest $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ad = Advertisement::find($id);
        $ad->delete();
        return $this->showMessage('ad deleted successfully...!');
    }
}
