<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Filters\Dashboard\AdvertisementFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementUpdateRequest;
use App\Models\Advertisement;
use App\Models\User;
use App\Services\Dashboard\AdvertisementService;
use App\Traits\{
    ResponseTrait,
    FirebaseNotificationTrait
};
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Notifications\{
    ApprovedAdsNotification,
    AddAdsNotification
};

class AdvertisementController extends Controller
{
    use ResponseTrait, FirebaseNotificationTrait;

    public function __construct(protected AdvertisementFilter $advertisementFilter, protected AdvertisementService $service) {}

    public function index(Request $request)
    {

        try {
            $data = Advertisement::query();
            return $this->advertisementFilter->apply($data)->get();
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while displaying advertisement !!');
        }
    }


    public function show(string $id)
    {
        try {
            $data = Advertisement::with(['user', 'attributes'])->findOrFail($id)->append('is_reported');
            return $this->showResponse($data, 'Advertisement retrieved successfully !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while show the advertisement !!');
        }
    }


    public function update(AdvertisementUpdateRequest $request, string $id)
    {
        try {
            $ad = Advertisement::with(['attributes'])->findOrFail($id);
            $data = $this->service->update($request, $ad);
            return $this->showResponse($data, 'Advertisement Updated successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while updating the advertisement !!');
        }
    }

    public function destroy(string $id)
    {
        try {
            $ad = Advertisement::findOrFail($id);
            $this->service->destroy($ad);
            return $this->showMessage('Advertisement deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting the advertisement !!');
        }
    }

    public function approve(string $id)
    {
        $ad = Advertisement::findOrFail($id);
        $user = User::findOrFail($ad->user_id);
        if ($ad->status != 'pending')
            return $this->showMessage('this advertisement is already processed !!', 422, false);
        try {
            $ad->update([
                'expiry_date' => now()->addDays(30),
                'status' => 'active'
            ]);
            $this->sendNotificationToTopic(
                $ad->main_category_id,
                'إعلان جديد في قسم ' . $ad->main_category_name,
                $ad->title,
                $ad->id,
                'add-Ads'
            );
            $users = User::all();
            foreach ($users as $recipient) {
                $recipient->notify(new AddAdsNotification(
                    $ad->main_category_id,
                    $ad->main_category_name,
                    $ad->title,
                    $ad->id
                ));
            }
            $token = $user->device_token;
            if ($token) {
                try {
                    $Request = (object) [
                        'title' => 'قبول اعلان',
                        'body' => 'تم قبول اعلانك "' . $ad->title . '" من قبل الادمن',
                        'type' => 'approved-Ads',
                    ];

                    $this->unicast($Request, $token);
                    Log::error('done');
                } catch (Exception $e) {
                    Log::error('Failed to send Notification Firebase: ' . $e->getMessage());
                }
            }
            $user->notify(new ApprovedAdsNotification($ad));
            return $this->showResponse($ad, 'Advertisement approved !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while approving the advertisement');
        }
    }


    public function reject(Request $request, string $id)
    {
        $request->validate(['rejecting_reason' => 'sometimes|string']);

        $ad = Advertisement::findOrFail($id);
        if ($ad->status != 'pending')
            return $this->showMessage('this advertisement is already processed !!', 422, false);
        try {
            $ad->update([
                'rejecting_reason' => $request->rejecting_reason,
                'status' => 'rejected'
            ]);
            return $this->showResponse($ad, 'Advertisement rejected !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while rejecting the advertisement');
        }
    }
}
