<?php

namespace App\Services\Mobile;

use App\Models\Cv;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class CVService.
 */
class CVService
{
    use HasFiles;

    /**
     * Summary of create
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(FormRequest $request, User $user)
    {
        $data = $request->all();
        return DB::transaction(function () use ($user, $request, $data) {
            if ($request->hasFile('image'))
                $data['image'] = $this->saveFile($request->file('image'), 'CV');

            $cv = $user->cv()->create($data);
            return $cv;
        });
    }

    /**
     * Summary of update
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\User $user
     * @return mixed
     */
    public function update(FormRequest $request, User $user)
    {
        $data = $request->all();

        return DB::transaction(function () use ($request, $user, $data) {
            if ($request->hasFile('image')) {
                if ($user->cv()->first()->image) {
                    $this->deleteFile($user->cv()->first()->image);
                }
                $data['image'] = $this->saveFile($request->file('image'), 'CV/Images');
            }
            return $user->cv()->update($data);

        });
    }


    /**
     * Add a file to the CV
     * @param CV $cv
     * @param Request $request
     * @return mixed
     */
    public function addFile(Request $request, Cv $cv)
    {
        return DB::transaction(function () use ($cv, $request) {
            $filePath = $this->saveFile($request->file('file'), 'CV/Resumes');
            return $cv->file()->create(['url' => $filePath]);
        });
    }

    /**
     * Add experience to the CV
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\Cv $cv
     * @return mixed
     */
    public function addExperience(FormRequest $request, Cv $cv)
    {
        return DB::transaction(function () use ($request, $cv) {
            return $cv->experience()->create($request->all());
        });
    }

    /**
     * Add Language to the CV
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\Cv $cv
     * @return mixed
     */
    public function addLanguage(FormRequest $request, Cv $cv)
    {
        return DB::transaction(function () use ($request, $cv) {
            return $cv->language()->create($request->all());
        });
    }

    /**
     * Add Qualification to the CV
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \App\Models\Cv $cv
     * @return mixed
     */
    public function addQualification(FormRequest $request, Cv $cv)
    {
        return DB::transaction(function () use ($request, $cv) {
            return $cv->qualification()->create($request->all());
        });
    }

    /**
     * Add Skill to the CV
     * @param array $request
     * @param \App\Models\Cv $cv
     * @return mixed
     */
    public function addSkill(array $request, Cv $cv)
    {
        return DB::transaction(function () use ($request, $cv) {
            return $cv->skill()->create($request);
        });
    }

    /**
     * Add Link to the CV
     * @param array $request
     * @param \App\Models\Cv $cv
     * @return mixed
     */
    public function addLink(array $request, Cv $cv)
    {
        return DB::transaction(function () use ($request, $cv) {
            return $cv->link()->create($request);
        });
    }

    /**
     * Add Document to the CV
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Cv $cv
     * @return mixed
     */
    public function addDocument(Request $request, Cv $cv)
    {
        return DB::transaction(function () use ($request, $cv) {
            $file_path = $this->saveFile($request->file('file'), 'CV/Documents');
            $cv->document()->create([
                'name' => $request->name,
                'path' => $file_path
            ]);
            return $cv;
        });
    }
}
