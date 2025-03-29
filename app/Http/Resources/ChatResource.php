<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'sender_name' => $this->client->first_name,
            'receiver_name' => $this->seller->first_name,
            'advertisement_details' => $this->advertisement_details,
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
