<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
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
            'city_id' => $this->city_id,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'type' => $this->type,
            'negotiable' => $this->negotiable,
            'currency_type' => $this->currency_type,
            'is_special' => $this->is_special,
            'status' => $this->status,
            'expiry_date' => $this->expiry_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'attributes' => $this->attributes,
            'views' => $this->views,
            'created_from' => $this->created_from,
            'is_favorite' => $this->is_favorite,
            'parent_category' => $this->parent_category,
            'category_name' => $this->category_name,
            'city_name' => $this->city_name,
            'images' => $this->images,
        ];
    }
}
