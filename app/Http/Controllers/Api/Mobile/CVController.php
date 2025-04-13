<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\CvExperienceCreateRequest;
use App\Http\Requests\Mobile\CvQualificationCreateRequest;
use App\Http\Requests\Mobile\CvUpdateRequest;
use App\Http\Requests\Mobile\CvCreateRequest;
use App\Services\Mobile\CVService;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CVController extends Controller
{
    use ResponseTrait, HasFiles;

    public function __construct(private CVService $service)
    {
    }


    /**
     * Display the CV
     */
    public function show()
    {
        $user = Auth::user();
        $cv = $user->cv()
            ->with([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ])->first();
        return $this->showResponse($cv, 'CV retrieved successfully !');
    }

    /**
     * Store a newly CV information for the authenticated user
     */
    public function store(CvCreateRequest $request)
    {
        $user = $request->user();
        if ($user->cv()->first())
            return $this->showMessage('you already created a CV', 500, false);

        try {
            $cv = $this->service->create($request, $user);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'CV created successfully !! ');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error has been occur while creation a CV');
        }
    }


    /**
     * Update the CV information for the authenticated user
     */
    public function update(CvUpdateRequest $request)
    {
        $user = $request->user();
        try {
            $cv = $this->service->update($request, $user);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'CV created successfully !! ');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error has been occur while updating the CV');
        }
    }

    /**
     * Add a CV-file
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addCvFile(Request $request)
    {
        $user = $request->user();
        $cv = $user->cv()->first();

        if ($cv->file()->first()) {
            return response()->json(['message' => 'A file already exists for this CV.'], 400);
        }

        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $this->service->addFile($request, $cv);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'file uploaded successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error has been occur while adding CV`s file');
        }
    }

    /**
     * Delete the CV-file
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCvFile()
    {
        $user = Auth::user();
        $cv = $user->cv()->first();
        $file = $cv->file()->first();
        if (!$file)
            return $this->showMessage('you did not upload any CV-File yet', 400, false);
        try {
            $this->deleteFile($file->url);
            $file->delete();
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'File deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error has been occur while deleting CV`s file');
        }
    }


    /**
     * Add new experience to the CV
     * @param \App\Http\Requests\Mobile\CvExperienceCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addExperience(CvExperienceCreateRequest $request)
    {
        $user = $request->user();
        $cv = $user->cv()->first();
        try {
            $this->service->addExperience($request, $cv);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'An Experience add to CV successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error has been occur while adding an experience to CV');

        }
    }

    /**
     * Delete an experience for the CV
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteExperience(string $id)
    {
        $user = Auth::user();
        $cv = $user->cv()->first();
        $experience = $cv->experience()->findOrFail($id);
        if (!$experience)
            return $this->showMessage('sorry, we could not find this experience \n Maybe it`s already deleted', 400);

        try {
            $experience->delete();
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'experience deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting an experience');
        }
    }



    /**
     * Add a new qualification to CV
     * @param \App\Http\Requests\Mobile\CvQualificationCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addQualification(CvQualificationCreateRequest $request)
    {
        $user = $request->user();
        $cv = $user->cv()->first();
        try {
            $this->service->addQualification($request, $cv);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'An Qualification add to CV successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error has been occur while adding a Qualification to CV');

        }
    }

    /**
     * Delete a qualification from CV
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteQualification(string $id)
    {
        $user = Auth::user();
        $cv = $user->cv()->first();
        $qualification = $cv->qualification()->find($id);
        if (!$qualification)
            return $this->showMessage('sorry, we could not find this qualification \n Maybe it`s already deleted', 400);

        try {
            $qualification->delete();
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'qualification deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting an qualification');
        }
    }


    /**
     * Add skill to the CV
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSkill(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $user = $request->user();
        $cv = $user->cv()->first();
        try {
            $this->service->addSkill($data, $cv);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'skill added successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while adding a skill to your CV', 500);
        }
    }

    /**
     * Delete skill from the CV
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSkill(string $id)
    {
        $user = Auth::user();
        $cv = $user->cv()->first();
        $skill = $cv->skill()->find($id);
        if (!$skill)
            return $this->showMessage('sorry, we could not find this skill \n Maybe it`s already deleted', 400, false);
        try {
            $skill->delete();
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'Skill deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting a skill');
        }
    }

    /**
     * Add link to the CV
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLink(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);
        $user = $request->user();
        $cv = $user->cv()->first();
        try {
            $this->service->addLink($data, $cv);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'Link added successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while adding a link to your CV', 500);
        }
    }

    /**
     * Delete link from the CV
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLink(string $id)
    {
        $user = Auth::user();
        $cv = $user->cv()->first();
        $link = $cv->link()->find($id);
        if (!$link)
            return $this->showMessage('sorry, we could not find this link \n Maybe it`s already deleted', 400, false);
        try {
            $link->delete();
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'Link deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting a link');
        }
    }

    /**
     * Add document to the CV
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDocument(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file'
        ], [
            'file.required' => 'You must upload a file!',
            'file.file' => 'The uploaded item must be a valid file format.'
        ]);

        $user = $request->user();
        $cv = $user->cv()->first();
        try {
            $this->service->addDocument($request, $cv);
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'Document added successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while adding a Document to your CV', 500);
        }
    }

    /**
     * Delete document from the CV
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDocument(string $id)
    {
        $user = Auth::user();
        $cv = $user->cv()->first();
        $document = $cv->document()->find($id);
        if (!$document)
            return $this->showMessage('sorry, we could not find this document \n Maybe it`s already deleted', 400, false);
        try {
            $this->deleteFile($document->path);
            $document->delete();
            $cv->load([
                'file',
                'document',
                'link',
                'qualification',
                'experience',
                'skill',
            ]);
            return $this->showResponse($cv, 'Document deleted successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while deleting a document');
        }
    }




}
