<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Services\Dashboard\VerificationRequestService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class UserVerificationRequestController extends Controller
{
    use ResponseTrait;


    public function __construct(protected VerificationRequestService $service)
    {
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $requests = VerificationRequest::where('status', $status)->with('user')->get();
        return $this->showResponse($requests, 'Verification Requests retrieved successfully !!', 200);
    }


    public function approve(string $id)
    {
        $request = VerificationRequest::findOrFail($id);
        try {
            $data = $this->service->approve($request);
            return $this->showResponse($data, 'User verified successfully !!', 200);
        } catch (Exception $exception) {
            report($exception);
            return $this->showError($exception, 'An error occur while approving this verification request');
        }
    }


    public function reject(string $id)
    {
        $request = VerificationRequest::findOrFail($id);
        try {
            $data = $this->service->reject($request);
            return $this->showResponse($data, 'User request rejected !!', 200);
        } catch (Exception $exception) {
            report($exception);
            return $this->showError($exception, 'An error occur while rejecting this verification request');
        }
    }
}
