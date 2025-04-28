<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class CvCreateRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'image' => 'nullable|image',
            'email' => 'required|string|email|max:255|unique:cvs,email',
            'phone_number' => 'required|numeric|unique:cvs,phone_number',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
        ];
    }
}
