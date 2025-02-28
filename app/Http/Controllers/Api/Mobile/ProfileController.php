<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\ProfileUpdateRequest;
use App\Services\Mobile\ProfileService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ResponseTrait;


    public function __construct(private ProfileService $service)
    {
    }


    /**
     * Display the authenticated user profile.
     */
    public function show()
    {
        $user = Auth::user();
        return $this->showResponse($user, 'profile retrieved successfully !!', 200);
    }

    /**
     * Update the authenticated user profile
     * @param \App\Http\Requests\Mobile\ProfileUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        try {
            $this->service->update($request, $user);
            return $this->showResponse($user, 'profile changed successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'an error occur while changing profile information', 500);
        }
    }

    public function showAds()
    {
        return $this->showResponse(Auth::user()->ads()->get(), 'User advertisement retrieved successfully !!', 200);
    }

    public function showRates()
    {
        return $this->showResponse(Auth::user()->rated()->get(), 'User rates retrieved successfully !!', 200);

    }

}
