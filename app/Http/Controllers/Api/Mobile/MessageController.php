<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Message,
    Chat,
    User
};
use Illuminate\Support\Facades\{
    DB,
    Auth,
    Log
};
use App\Events\NewMessageSent;
use App\Traits\{
    ResponseTrait,
    FirebaseNotificationTrait
};
use App\Http\Requests\MessageRequest;
use Exception;

class MessageController extends Controller
{
    use ResponseTrait, FirebaseNotificationTrait;


    /**
     * Store a newly created resource in storage.
     */
   public function store(MessageRequest $request)
{
    DB::beginTransaction();
    try {
        $user = Auth::user();
        $message = $user->messages()->create([
            'sender_id' => $user->id,
            'content' => $request->content,
            'chat_id' => $request->chat_id
        ]);
        event(new NewMessageSent($message));

        $chat = Chat::find($request->chat_id);

        $receiverId = ($user->id == $chat->user_one_id) ? $chat->user_two_id : $chat->user_one_id;
        $receiver = User::findOrFail($receiverId);

        $token = $receiver->device_token;
        if($token){
            try {
                $Request = (object) [
                    'title' => 'رسالة جديدة',
                    'body' => 'لديك رسالة من ' . $user->first_name . ' ' .$user->last_name,
                    'type' => 'chat',
                ];
                $this->unicast($Request, $token);
                Log::error('done');
            } catch (Exception $e) {
                Log::error('Failed to send Notification Firebase: ' . $e->getMessage());
            }
        }

        Log::info('Message sent and broadcasted: ', ['message' => $message]);

        DB::commit();
        return $this->showMessage('تم الارسال');
    } catch (Exception $e) {
        DB::rollBack();
        return $this->showError($e, 'حدث خطأ ما يرجى المحاولة لاحقا');
    }
}

}
