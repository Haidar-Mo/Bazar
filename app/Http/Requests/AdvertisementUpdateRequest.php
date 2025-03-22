<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes'],
            'city_id' => ['sometimes'],
            'title' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'type' => ['sometimes', 'in:offer,order'],
            'currency_type' => ['sometimes'],
            'negotiable'=>['sometimes','boolean'],
            //'expiry_date' => ['nullable', 'date'],
            //'is_special' => ['nullable'],
            // 'images' => 'nullable|array|min:1',
            // 'images.*' => 'sometimes|image',
            'attributes' => 'sometimes|array',

        ];
    }
}
