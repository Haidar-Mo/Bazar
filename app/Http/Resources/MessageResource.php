<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'sender_id' => $this->sender_id,
            'chat_id'=>$this->chat_id,
            'is_read'=>$this->is_read,
            'created_from'=>$this->created_from


        ];
    }
} 
