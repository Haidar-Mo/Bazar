<?php

namespace App\Http\Controllers\Dashboard;

use App\Filters\Dashboard\AdvertisementFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdvertisementRequest;
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
            return $this->advertisementFilter->apply($data);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while displaying advertisement !!');
        }
    }


    public function show(string $id)
    {
        try {
            $data = Advertisement::findOrFail($id);
            $data->with(['user', 'attributes']);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while show the advertisement !!');
        }
    }

    public function update(UpdateAdvertisementRequest $request, string $id)
    {
        try {
            $ad = Advertisement::findOrFail($id);
            $data = $this->service->update($request, $ad);
            return $this->showResponse($data, 'Advertisement Updated successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while updating the advertisement !!');
        }
    }

    public function delete(string $id)
    {
        try {
            $ad = Advertisement::findOrFail($id);
            $this->service->delete($ad);
            return $this->showMessage('Advertisement deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting the advertisement !!');
        }
    }
}
