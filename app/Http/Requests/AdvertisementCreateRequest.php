<?php

namespace App\Http\Requests;

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
        $category=Category::where('id',$category_id)->first();


        $commonRules = [
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required'],
            'city_id'=>['required'],
            'expiry_date'=>['nullable','date'],
            'is_special'=>['nullable'],
            'images' => 'nullable|array|min:1',
            'images.*' => 'required|image'
        ];

        $categoryRules = match ($category->name) {
            'real_estate' => [
                'size' => ['required', 'integer', 'min:1'],
                'location' => ['required', 'string', 'max:255'],
            ],

            'Vehicle' => [
                'attributes.mileage' => ['required', 'integer'],
                'attributes.model'=>['required'],
                'attributes.category'=>['required'],
                'attributes.regional_specifications'=>['required'],
                'attributes.price_range'=>['required'],
                'attributes.body_type'=>['required'],
                'attributes.is_safty'=>['required'],
                'attributes.number'=>['required'],

                ///'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            ],

            'Hyper' => [
                'attributes.mileage' => ['required', 'integer'],
                'attributes.address' => ['required'],
                'attributes.number'=>['required'],
                'attributes.price'=>['required'],
                'attributes.description'=>['required'],
                'attributes.range_of_use'=>['required'],
                'attributes.price_range'=>['required'],
                'attributes.seller_type'=>['required'],
                'attributes.guarantee'=>['required'],
                'attributes.power_transmission_system'=>['required'],
                'attributes.number_of_tires'=>['required'],
                'attributes.manufacture'=>['required'],
                'attributes.engine_size'=>['required'],
                'attributes.location'=>['nullable'],
                'attributes.street_name'=>['nullable'],


            ],

            default => [],
        };

        return array_merge($commonRules, $categoryRules);
    }
}
