<?php

namespace App\Services\Mobile;

use App\Models\User;
use App\Traits\HasFiles;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ProfileService.
 */
class ProfileService
{

    use HasFiles;


    /**
     * Update the authenticated user Profile
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\User $user
     * @return mixed
     */
    public function update(FormRequest $request, User $user)
    {
        $image = null;
        if ($request->hasFile('image')) {
            if ($user->image)
                $this->deleteFile($user->image);
            $image = $this->saveFile($request->file('image'), 'Profile/Images');

        }
        try {
            return DB::transaction(function () use ($request, $user, $image) {
                $user->update($request->all());
                if ($image)
                    $user->images()->updateOrCreate([
                        'imageable_id' => $user->id
                    ], ['path' => $image]);
                return $user;
            });
        } catch (Exception $e) {
            throw $e;
        }
    }
}
