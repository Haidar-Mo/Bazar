<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\ProfileUpdateRequest;
use App\Models\User;
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
     * Display the user profile
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id)->only([
            'first_name',
            'last_name',
            'phone_number',
            'email',
            'birth_date',
            'address',
            'gender',
            'job',
            'company',
            'description',
            'is_verified',
            'image',
            'age',
        ]);
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

    /**
     * Display list of specific user advertisements
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAds(string $id)
    {
        $user = User::findOrFail($id);
        return $this->showResponse($user->ads()->get(), 'User advertisement retrieved successfully !!', 200);
    }


    /**
     * Display list of specific user rates
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRates(string $id)
    {
        $user = User::findOrFail($id);
        return $this->showResponse($user->rated()->get()->makeHidden(['rated_user_name','rated_user_id']), 'User rates retrieved successfully !!', 200);

    }

}
