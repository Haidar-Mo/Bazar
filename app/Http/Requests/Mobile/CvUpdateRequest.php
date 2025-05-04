<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class CvUpdateRequest extends FormRequest
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
        $cvId = $this->route('id');

        return [
            'full_name' => 'sometimes|string|max:255',
            'summary' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:c_v_s,email,' . $cvId,
            'phone_number' => 'sometimes|string|max:255|unique:c_v_s,phone_number,' . $cvId,
            'gender' => 'sometimes|in:male,female',
            'nationality' => 'sometimes|string|max:255',
            'birth_date' => 'nullable|date',
        ];
    }
}
