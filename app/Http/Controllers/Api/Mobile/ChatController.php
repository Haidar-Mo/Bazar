<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\{
    DB,Auth
};
use App\Http\Requests\ChatRequest;
use App\Traits\ResponseTrait;
use Exception;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        $chat=  $user->chat()->with(['client', 'seller', 'ads'])->get();
        $chatDetails = $chat->map(function ($chat) {
            return $chat->chat_details;
        });
        return $this->showResponse($chatDetails, 'done successfully..!');
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
    public function store(ChatRequest $request)
    {
        $user_one_id=Auth::user();
        DB::beginTransaction();
        try{
        $chat=Chat::FirstOrCreate([
            'user_one_id'=>$user_one_id->id,
            'user_two_id'=>$request->user_two_id,
            'advertisement_id'=>$request->advertisement_id
        ]);
        DB::commit();
        return $this->showResponse($chat,'chat created successfully....!');
    }catch(Exception $e){
        return $this->showError($e,'something goes wrong....!');

    }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $chat = Chat::with(['messages'])
            ->findOrFail($id);
        return $this->showResponse(new ChatResource($chat), 'done successfully...!');
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
