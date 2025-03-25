<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateOfficialsRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'role'=>['required','in:admin,supervisor'],
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:10',
            'job' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
