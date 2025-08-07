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

        ->latest('updated_at')

        ->get()
        ->map(function ($chat) use ($user) {
            $otherUser = $chat->user_one_id == $user->id
                ? $chat->seller
                : $chat->client;

            return [
                'chat' => $chat->chat_details,
                'other_user_name' => $otherUser->first_name,
                'other_user_image' => $otherUser->images,
            ];
        });

    return $this->showResponse($chats, 'تم جلب المحادثات بنجاح');
}





    /**
     * Store a newly created resource in storage.
     */
public function store(ChatRequest $request)
{
    $user = Auth::user();

    DB::beginTransaction();
    try {
        $chat = Chat::firstOrCreate([
            'user_one_id' => $user->id,
            'user_two_id' => $request->user_two_id,
            'advertisement_id' => $request->advertisement_id
        ]);

        // نحدد الطرف الثاني
        $otherUser = $chat->user_one_id == $user->id
            ? $chat->seller
            : $chat->client;

        DB::commit();

        return $this->showResponse([
            'chat'=>$chat,
            'other_user_name' => $otherUser->first_name,
            'other_user_image' => $otherUser->images,
        ], 'تم إنشاء المحادثة بنجاح');

    } catch (Exception $e) {
        DB::rollBack();
        return $this->showError($e, 'حدث خطأ ما، يرجى المحاولة لاحقًا');
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
