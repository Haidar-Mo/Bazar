<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CvQualificationCreateRequest extends FormRequest
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
            'certificate' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'entering_date' => 'required|date',
            'graduation_date' => 'nullable|date|after_or_equal:entering_date',
            'still_studying' => [
                'boolean',
                Rule::when($this->graduation_date == null, 'accepted', ['nullable']),
            ]
        ];
    }
}
