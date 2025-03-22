<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Filters\Dashboard\AdvertisementFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementUpdateRequest;
use App\Models\Advertisement;
use App\Services\Dashboard\AdvertisementService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Exception;

class AdvertisementController extends Controller
{
    use ResponseTrait;

    public function __construct(protected AdvertisementFilter $advertisementFilter, protected AdvertisementService $service)
    {
    }

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
        if ($ad->status != 'pending')
            return $this->showMessage('this advertisement is already processed !!', 422, false);
        try {
            $ad->update(['status' => 'active']);
            return $this->showResponse($ad, 'Advertisement approved !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while approving the advertisement');
        }
    }


    public function reject(string $id)
    {
        $ad = Advertisement::findOrFail($id);
        if ($ad->status != 'pending')
            return $this->showMessage('this advertisement is already processed !!', 422, false);
        try {
            $ad->update(['status' => 'rejected']);
            return $this->showResponse($ad, 'Advertisement rejected !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while rejecting the advertisement');
        }
    }
}
