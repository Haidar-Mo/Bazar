<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'advertisement_details' => $this->advertisement_details,
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
