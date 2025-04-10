<?php

namespace App\Http\Requests\Mobile;

use App\Models\Category;
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
        $category_id = $this->input('category_id');
        $category = Category::findOrFail($category_id);


        $commonRules = [
            'category_id' => ['required'],
            'city_id' => ['required'],
            'title' => ['required', 'string'],
            'type' => ['required', 'in:طلب,عرض'],
            'location' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency_type' => ['required'],
            'negotiable' => ['required', 'boolean'],
            'images' => 'nullable|array|min:1',
            'images.*' => 'nullable|image',
            'attributes' => 'required',
        ];

        $categoryRules = match ($category->name) {
            'عقارات' => [
                'attributes.details' => ['required'],
                'attributes.specifications' => ['required'],
                'attributes.transportation_availability' => ['sometimes', 'array'],
                'attributes.amenities' => ['required'],
                'attributes.amenities.*' => ['boolean'],
            ],
            'مركبات' => [
                'attributes.details' => ['required'],
                'attributes.basic' => ['required'],
                'attributes.features' => ['required'],
                'attributes.specifications' => ['required'],
            ],
            'الكترونيات' => [
                'attributes.details' => ['required'],
            ],
            'ازياء و موضة' => [
                'attributes.details' => ['required'],
            ],
            'اثاث و ديكور' => [
                'attributes.details' => ['required'],
            ],
            'الصناعة و البناء' => [
                'attributes.details' => ['required'],
            ],
            'اعلانات تجارية و متفرقات' => [
                'attributes.details' => ['required'],
            ],

            default => [],
        };

        return array_merge($commonRules, $categoryRules);
    }
}
