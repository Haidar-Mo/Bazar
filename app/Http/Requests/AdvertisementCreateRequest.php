<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementCreateRequest extends FormRequest
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
        $category = $this->input('category_id');

        $commonRules = [
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'string'],
        ];

        $categoryRules = match ($category) {
            'real_estate' => [
                'size' => ['required', 'integer', 'min:1'],
                'location' => ['required', 'string', 'max:255'],
            ],
            'Vehicle' => [
                'mileage' => ['required', 'integer'],
                'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            ],
            default => [],
        };

        return array_merge($commonRules, $categoryRules);
    }
}
