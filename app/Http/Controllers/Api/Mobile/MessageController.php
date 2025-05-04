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
use App\Traits\ResponseTrait;
use App\Http\Requests\MessageRequest;
use Exception;

class MessageController extends Controller
{
    use ResponseTrait;
 

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
            Log::info('Message sent and broadcasted: ', ['message' => $message]);
            DB::commit();
            return $this->showMessage('Message sent..!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong...!');
        }
    }

    
}
