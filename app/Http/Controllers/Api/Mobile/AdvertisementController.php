<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mobile\AdvertisementCreateRequest;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\JobRequest;
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
            $ads = $user->ads()->where('status', $request->status)->orderBy('created_at', 'desc')->paginate(10);
        } else {

            $ads = $user->ads()->orderBy('created_at', 'desc')->paginate(10);
        }
        return $this->showResponse($ads, 'done');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvertisementCreateRequest $request)
    {
        try {
            $user = $request->user();
            $plan = $user->subscriptions()
                ->where('status', 'running')->oldest()
                ->plan()->first();
            $mergedData = array_merge($request->all(), [
                // 'expiry_date' => now()->addDays(30),
                'is_special' => $plan->is_special
            ]);
            $ads = $this->service->create($mergedData, $user);
            return $this->showResponse($ads->append('attributes'), 'create ads successfully ...!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while creating your advertisement !!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = Auth::user();
        DB::beginTransaction();
        try {
            $ad = Advertisement::with(['user'])->where('id', $id)->first();
            $user?->views()->firstOrCreate(['advertisement_id' => $id]);
            DB::commit();
            return $this->showResponse($ad->append('attributes'), 'done successfully....!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong....!');

        }
    }

    /**
     * Create a share link for a specific advertisement
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function share(string $id)
    {
        $ad = Advertisement::findOrFail($id);
        $shareableUrl = url("/share/{$ad->id}");
        return response()->json(['url' => $shareableUrl]);
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


    public function indexWithFilter()
    {
        try {
            $data = $this->service->indexWithFilter();
            return $this->showResponse($data, 'filtered Ads retrieved successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while filtering the advertisements', 500);
        }

    }

    public function getSimilarAds(string $id)
    {
        $ad = Advertisement::find($id);

        if (!$ad) {
            return response()->json(['message' => 'Ad not found'], 404);
        }

        $similarAds = Advertisement::where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return $this->showResponse($similarAds, 'Similar advertisements retrieved successfully !!');
    }



    //- Job Requests
    public function indexJobRequest(Request $request)
    {
        $user = $request->user();
        $job_requests = $user->jobRequest()->with(['sender:id,first_name,last_name', 'advertisement'])->get();
        return $this->showResponse($job_requests, 'Requests retrieved successfully', 200);
    }

    public function createJobRequest(Request $request, string $id)
    {
        $user = $request->user();

        $category = Category::where('name', 'فرص عمل')->first();
        $advertisement = Advertisement::whereIn('category_id', array_merge([$category->id], $category->children()->get()->pluck('id')->toArray()))
            ->where('status', 'active')
            ->where('id', $id)
            ->first();
        if (!$advertisement)
            return $this->showMessage('لم يتم العثور على الإعلان المطلوب, ربما تم حذفه', 400);
        $old_request = $user->sendedJobRequest()
            ->where('advertisement_id', $advertisement->id)
            ->where('status', 'new')
            ->first();
        if ($old_request)
            return $this->showMessage('طلبك السابق قيد المعالجة', 400);

        $advertisement->jobRequest()->create(
            [
                'sender_id' => $user->id,
                'receiver_id' => $advertisement->user_id
            ]
        );
        return $this->showMessage('تم إرسال الطلب بنجاح', 200);
    }


    public function showJobRequest(string $id)
    {
        $job_request = JobRequest::findOrFail($id);
        $sender = $job_request->sender;
        $cv = $sender->cv()->with([
            'file',
            'document',
            'link',
            'language',
            'qualification',
            'experience',
            'skill',
        ])->first();

        return $this->showResponse($cv, 'Data retrieved successfully', 200);
    }


}
