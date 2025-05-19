<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\VerificationRequestCreateRequest;
use App\Http\Requests\Mobile\VerificationRequestUpdateRequest;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Notifications\Dasboard\NotificationAuthenticationRequest;
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
    public function show()
    {
        $user = Auth::user();
        $request = $user->verificationRequest()->latest()->first();
        return $this->showResponse($request, 'Your request retrieved successfully !!', 200);
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(VerificationRequestCreateRequest $request)
    {
        $user = $request->user();
        try {
            $verification_request = $this->service->create($request, $user);
            $admins = User::role(['admin', 'supervisor'], 'api')->get();

            foreach ($admins as $admin) {
                $admin->notify(new NotificationAuthenticationRequest("قام ( {$user->name} ) بإرسال طلب توثيق."));
            }
            return $this->showResponse($verification_request, 'تم إرسال طلب التوثيق بنجاح', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'حدث خطأ ما أثناء إرسال طلب توثيق', 500);
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
