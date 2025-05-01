<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Advertisement,
    User,
    Report
};
use App\Traits\ResponseTrait;
use App\Http\Requests\ReportRequest;
use Exception;
use Illuminate\Support\Facades\{
    DB,
    Auth
};
use App\Notifications\Dasboard\NotificationReports;

class ReportController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportRequest $request)
    {
        $user=Auth::user();
        DB::beginTransaction();
        try{
        $report = Report::create([
            'user_id' => $user->id,
            'reportable_id' => $request->ads_id,
            'reportable_type' => User::class,
            'paragraph' => $request->paragraph,
        ]);
        $adTitle = Advertisement::FindOrFail($request->ads_id)->title;
        $admins = User::role(['admin', 'supervisor'], 'api')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotificationReports("قام المستخدم ( {$user->name} ) بالابلاغ عن الإعلان: ( {$adTitle} )"));
        }
        DB::commit();
        return $this->showMessage('report sent successfully....!');

    }catch(Exception $e){
        DB::rollBack();
        return $this->showError($e,'something goes wrong.....!');

    }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
