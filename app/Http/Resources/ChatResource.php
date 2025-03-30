<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'sender_id' => $this->client->id,
            'sender_name' => $this->client->first_name,
            'sender_image' => $this->client->image,
            'receiver_id' => $this->seller->id,
            'receiver_name' => $this->seller->first_name,
            'receiver_image' => $this->seller->image,
            'advertisement_details' => $this->advertisement_details,
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
