<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\VerificationRequestCreateRequest;
use App\Http\Requests\Mobile\VerificationRequestUpdateRequest;
use App\Models\VerificationRequest;
use App\Services\Mobile\VerificationRequestService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationRequestController extends Controller
{
    use ResponseTrait;

    public function __construct(private VerificationRequestService $service)
    {
    }


    /**
     * Display the specified resource
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $request = $user->verificationRequest()->findOrFail($id);
        return $this->showResponse($request, 'Your request retrieved successfully !!', 200);
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(VerificationRequestCreateRequest $request)
    {
        $user = $request->user();
        if ($user->is_verified)
            return $this->showMessage('you are already verified', 400);
        try {
            $verification_request = $this->service->create($request, $user);
            //do: send Notification to admin
            return $this->showResponse($verification_request, 'Verification request sent successfully!!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'an Error occur while sending Verification request', 500);
        }
    }

    /**
     * Update the specified resource in storage
     */
    public function update(VerificationRequestUpdateRequest $request, string $id)
    {
        $user = $request->user();
        $verificationRequest = $user->verificationRequest()->fidOrFail($id);
        if ($verificationRequest->status != 'pending') {
            return $this->showMessage('Your request is already processed', 400);
        }
        try {
            $this->service->update($request, $verificationRequest);
            return $this->showResponse($verificationRequest, 'Your request updated successfully !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'an error occur while updating your verification request');

        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(VerificationRequest $verificationRequest)
    {
        //
    }
}
