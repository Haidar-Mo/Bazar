<?php

namespace App\Services\Mobile;

use App\Models\User;
use App\Models\VerificationRequest;
use App\Traits\HasFiles;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class VerificationRequestService.
 */
class VerificationRequestService
{
    use HasFiles;

    /**
     * Create a verification Request
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(FormRequest $request, User $user)
    {
        if ($user->is_verified)
            throw new Exception('you are already verified', 422);
        if ($user->verificationRequest()->where('status', 'pending')->first())
            throw new Exception('you have a pending request', 422);

        $files = [
            'identity_image' => 'identity_image',
            'work_register' => 'work_register',
            'other_document' => 'other_document',
        ];
        $filePaths = [];

        foreach ($files as $key => $fileKey) {
            $filePaths[$key] = $this->saveFile($request->file($fileKey), 'VerificationRequest');

        }
        return DB::transaction(function () use ($user, $request, $filePaths) {

            $verificationRequest = $user->verificationRequest()->create([
                'phone_number' => $request->phone_number,
                'identity_image' => $filePaths['identity_image'],
                'work_register' => $filePaths['work_register'],
                'other_document' => $filePaths['other_document'],
            ]);

            return $verificationRequest;
        });

    }


    public function update(FormRequest $request, VerificationRequest $verificationRequest)
    {
        //do: write it and comment the route ( maybe it is not allowed to update your request)
        return 'write the logic';
    }

}
