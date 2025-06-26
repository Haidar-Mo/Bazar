<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanUpdateRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'price' => 'sometimes|string',
            'size' => 'sometimes|string',
            'duration' => 'sometimes|decimal:0,99999.99',
            'discount_price'=>'nullable',
            'details'=>'sometimes'
        ];
    }
}
