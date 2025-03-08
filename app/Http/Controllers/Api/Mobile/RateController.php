<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Auth
};
use App\Http\Requests\RateRequest;
use App\Models\{
    User,
    Rate
};
use App\Traits\ResponseTrait;
use Exception;

class RateController extends Controller
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
    public function store(RateRequest $request)
    {
        $user=Auth::user();
        DB::beginTransaction();
        try{
         Rate::create([
            'user_id' => $user->id,
            'rated_user_id' => $request->rated_user_id,
            'rate' => $request->rate,
            'comment' => $request->comment,
        ]);
        DB::commit();
        return $this->showMessage('rate sent successfully....!');

    }
    catch(Exception $e){
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
