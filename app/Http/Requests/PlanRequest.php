<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'name' => 'required|string',
            'duration' => 'required|string',
            'size' => 'required|string',
            'price' => 'required|decimal:0,99999.99',
            'discount_price' => 'nullable',
            'details' => 'required',
            'is_special' => 'required|boolean'
        ];
    }
}
