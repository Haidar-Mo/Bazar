<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\{
    DB,
    Auth
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
        $user = Auth::user();

        $chats = $user->chat()
            ->with(['client', 'seller', 'ads', 'messages'])
            ->get();

        return $this->showResponse($chats->pluck('chat_details'), 'تم جلب المحادثات');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ChatRequest $request)
    {
        $user_one_id = Auth::user();
        DB::beginTransaction();
        try {
            $chat = Chat::FirstOrCreate([
                'user_one_id' => $user_one_id->id,
                'user_two_id' => $request->user_two_id,
                'advertisement_id' => $request->advertisement_id
            ]);
            DB::commit();
            return $this->showResponse($chat, 'chat created successfully....!');
        } catch (Exception $e) {
            return $this->showError($e, 'something goes wrong....!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $chat = Chat::with(['messages'])
            ->findOrFail($id);
        $chat->messages()->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->showResponse(new ChatResource($chat), 'done successfully...!');
    }

}
