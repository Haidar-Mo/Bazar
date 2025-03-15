<?php

namespace App\Services\Dashboard;

use App\Models\VerificationRequest;
use Exception;

/**
 * Class VerificationRequestService.
 */
class VerificationRequestService
{


    public function approve(VerificationRequest $request)
    {
        $user = $request->user()->first();

        if ($request->status != 'pending')
            throw new Exception('The request is already processed', 422);
        if ($user->is_verified)
            throw new Exception('User is already verified', 422);

        $request->update(['status' => 'approved']);
        $user->update(['is_verified' => true]);
        return $request;
    }

    public function reject(VerificationRequest $request)
    {
        $user = $request->user()->first();
        if ($request->status != 'pending')
            throw new Exception('The request is already processed', 422);

        if ($user->is_verified)
            throw new Exception('User is already verified', 422);

        $request->update(['status' => 'rejected']);
        // $user->update(['is_verified' => true]);
        return $request;
    }
}

