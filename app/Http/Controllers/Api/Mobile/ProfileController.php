<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ResponseTrait;

    /**
     * Display the authenticated user profile.
     */
    public function show()
    {
        $user = Auth::user();
        return $this->showResponse($user, 'profile retrieved successfully !!', 200);
    }

    /**
     * Update the authenticated user profile.
     */
    public function update(Request $request, string $id)
    {
        //
    }


}
