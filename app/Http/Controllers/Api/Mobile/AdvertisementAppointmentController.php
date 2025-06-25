<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Mobile\AdvertisementAppointmentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AdvertisementAppointmentController extends Controller
{
    use ResponseTrait;


    public function __construct(protected AdvertisementAppointmentService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $appointments = $this->service->index();
            return $this->showResponse($appointments);
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request, string $advertisementId)
    {
        try {
            $appointment = $this->service->create($request, $advertisementId);
            return $this->showResponse($appointment);

        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }


    public function accept(string $id)
    {
        try {
            $appointment = $this->service->acceptAppointment($id);
            return $this->showResponse($appointment);

        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }
    public function reject(Request $request, string $id)
    {
        try {
            $appointment = $this->service->rejectAppointment($id);
            return $this->showResponse($appointment);

        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->service->delete($id);
            return $this->showMessage("Deleted successfully", 200);
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }
}
