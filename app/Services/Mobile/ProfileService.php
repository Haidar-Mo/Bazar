<?php

namespace App\Services\Mobile;

use App\Models\User;
use App\Traits\HasFiles;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
        $data = $request->validated();
        return DB::transaction(function () use ($data, $user) {
            $user->update($data);
            return $user;
        });
    }

    public function updateImage(Request $request, User $user)
    {
        if ($user->image)
            $this->deleteFile($user->image);
        $image = $this->saveFile($request->file('image'), 'Profile/Images');

        return DB::transaction(function () use ($image, $user) {
            if ($image)
                $user->images()->updateOrCreate([
                    'imageable_id' => $user->id
                ], ['path' => $image]);
            return $user;
        });
    }
}
