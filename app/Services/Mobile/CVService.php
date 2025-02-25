<?php

namespace App\Services\Mobile;

use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class CVService.
 */
class CVService
{
    use HasFiles;
    public function create(FormRequest $request, User $user)
    {
        $data = $request->all();
        try {
            return DB::transaction(function () use ($user, $request, $data) {
                if ($request->hasFile('image'))
                    $data['image'] = $this->saveFile($request->file('image'), 'CV');

                $cv = $user->cv()->create($data);
                return $cv;
            });

        } catch (Exception $e) {
            report($e);
            return $e;
        }
    }


    public function update(FormRequest $request, User $user)
    {
        $data = $request->all();
        try {
            return DB::transaction(function () use ($request, $user, $data) {
                if ($request->hasFile('image')) {
                    if ($user->cv()->first()->image) {
                        $this->deleteFile($user->cv()->first()->image);
                    }
                    $data['image'] = $this->saveFile($request->file('image'), 'CV');
                }
                return $user->cv()->update($data);

            });
        } catch (Exception $e) {
            report($e);
            return $e;
        }
    }
}
