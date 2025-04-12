<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSent implements ShouldBroadcast,ShouldQueue
{
    use Dispatchable,InteractsWithSockets;


    public function __construct(public $message)
    {
    }

    public function broadcastOn()
    {
        return new Channel('chat.'.$this->message->chat_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'sender' => $this->message->sender->name,
            'sender_id'=>$this->message->sender_id,
            'created_at' => $this->message->created_at->toDateTimeString()
        ];
    }
}
