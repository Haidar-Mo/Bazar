<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Traits\ResponseTrait;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $chats = Chat::with(['client', 'seller', 'ads'])->paginate(10);
        $chatDetails = $chats->map(function ($chat) {
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
    public function store(Request $request)
    {
        //
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
