<?php

namespace App\Http\Requests\Mobile;

use App\Rules\SyrianPhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class VerificationRequestUpdateRequest extends FormRequest
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
            'phone_number' => 'sometimes|string|' . new SyrianPhoneNumberRule(),
            'identity_image' => 'sometimes|image',
            'work_register' => 'sometimes|file',
            'other_document' => 'sometimes|file',
        ];
    }
}
