<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'advertisement_id' => $this->advertisement_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_name' => $this->user_name,
            'advertisement_title' => $this->advertisement_title,
            'reservation_dates' => $this->reservationDates->pluck('date'),
            'user' => $this->user,
            'advertisement' => $this->advertisement,
        ];
    }
}
