<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\CvUpdateRequest;
use App\Http\Requests\Mobile\CvCreateRequest;
use App\Services\Mobile\CVService;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class CVController extends Controller
{
    use ResponseTrait, HasFiles;

    public function __construct(private CVService $service)
    {
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $cv = $user->cv()->with([
            'documents',
            'links',
            'qualification',
            'experience',
            'skill'
        ])->first();
        return $this->showResponse($cv, 'CV retrieved successfully !');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CvCreateRequest $request)
    {
        $user = $request->user();
        if ($user->cv()->first())
            return $this->showMessage('you already created a CV', 500, false);

        try {
            $cv = $this->service->create($request, $user);
            return $this->showResponse($cv, 'CV created successfully !! ');
        } catch (Exception $e) {
            return $this->showError($e);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CvUpdateRequest $request)
    {
        $user = $request->user();
        try {
            $cv = $this->service->update($request, $user);
            return $this->showResponse($cv, 'CV created successfully !! ');
        } catch (Exception $e) {
            return $this->showError($e);
        }
    }


    public function addCvFile()
    {
    }

    public function deleteCvFile()
    {
    }


    public function addExperiment()
    {
    }

    public function deleteExperiment()
    {
    }


    public function addSkill()
    {
    }

    public function deleteSkill()
    {
    }


    public function addQualification()
    {
    }

    public function deleteQualification()
    {
    }


    public function addLink()
    {
    }

    public function deleteLink()
    {
    }


    public function addDocument()
    {
    }

    public function deleteDocument()
    {
    }
}
