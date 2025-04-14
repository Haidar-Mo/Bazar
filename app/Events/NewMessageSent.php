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
    use Dispatchable,InteractsWithSockets,SerializesModels;


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
            'is_read'=>$this->message->is_read,
            'created_from' => $this->message->created_from,
            'time'=>$this->message->time
        ];
    }
}
