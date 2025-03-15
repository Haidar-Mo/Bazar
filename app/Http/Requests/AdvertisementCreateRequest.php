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
        $category = Category::where('id', $category_id)->first();


        $commonRules = [
            'category_id' => ['required'],
            'city_id' => ['required'],
            'type'=>['required','in:offer,order'],
            'price' => ['required', 'numeric', 'min:0'],
            'expiry_date' => ['nullable', 'date'],
            'is_special' => ['nullable'],
            'images' => 'nullable|array|min:1',

            'images.*' => 'required|image',
            'attributes' => 'required|array',

        ];

        $categoryRules = match (true) {
            'real_estate' => [
                'size' => ['required', 'integer', 'min:1'],
                'location' => ['required', 'string', 'max:255'],
            ],


            $category->name === 'Vehicle' => [
                'attributes.type_model' => ['required'],
                'attributes.category'=>['required'],
                'attributes.regional_specs'=>['required'],
                'attributes.price_range'=>['required'],
                'attributes.mileage'=>['required'],
                'attributes.body_type'=>['required'],
                'attributes.is_safty'=>['required'],
                'attributes.price'=>['required'],
                'attributes.title'=>['required'],
                'attributes.description'=>['required'],
                'attributes.fuel_type'=>['required'],
                'attributes.exterior_color'=>['required'],
                'attributes.interior_color'=>['required'],
                'attributes.warranty'=>['required'],
                'attributes.doors_number'=>['required'],
                'attributes.transmission_type'=>['required'],
                'attributes.seats_number'=>['required'],
                'attributes.horsepower'=>['required'],
                'attributes.driving_direction'=>['required'],
                'attributes.triptonic_gears'=>['nullable','boolean'],
                'attributes.dual_exhaust'=>['nullable','boolean'],
                'attributes.awd'=>['nullable','boolean'],
                'attributes.front_airbags'=>['nullable','boolean'],
                'attributes.cruise_control'=>['nullable','boolean'],
                'attributes.n2o_system'=>['nullable','boolean'],
                'attributes.side_airbags'=>['nullable','boolean'],
                'attributes.abs'=>['nullable','boolean'],
                'attributes.adaptive_driving'=>['nullable','boolean'],
                'attributes.front_traction'=>['nullable','boolean'],
                'attributes.rear_traction'=>['nullable','boolean'],
                'attributes.electric_closing'=>['nullable','boolean'],
                'attributes.off-road-wheels'=>['nullable','boolean'],
                'attributes.fog_lights'=>['nullable','boolean'],
                'attributes.keyless_start'=>['nullable','boolean'],
                'attributes.cassette_player'=>['nullable','boolean'],
                'attributes.navigation_system'=>['nullable','boolean'],
                'attributes.racing_seats'=>['nullable','boolean'],
                'attributes.heated_seats'=>['nullable','boolean'],
                'attributes.leather_seats'=>['nullable','boolean'],
                'attributes.special_tires'=>['nullable','boolean'],
                'attributes.roof_rack'=>['nullable','boolean'],
                'attributes.special_lights'=>['nullable','boolean'],
                'attributes.anti_theft_system'=>['nullable','boolean'],
                'attributes.dvd_player'=>['nullable','boolean'],
                'attributes.body_kit'=>['nullable','boolean'],
                'attributes.audio_input'=>['nullable','boolean'],
                'attributes.sunroof'=>['nullable','boolean'],
                'attributes.power_mirrors'=>['nullable','boolean'],
                'attributes.high_performance_tires'=>['nullable','boolean'],
                'attributes.keyless_entry'=>['nullable','boolean'],
                'attributes.cooled_seats'=>['nullable','boolean'],
                'attributes.heating'=>['nullable','boolean'],
                'attributes.air_conditioning'=>['nullable','boolean'],
                'attributes.fm_radio'=>['nullable','boolean'],
                'attributes.manual_sunroof'=>['nullable','boolean'],
                'attributes.power_seats'=>['nullable','boolean'],
                'attributes.front_bumper'=>['nullable','boolean'],
                'attributes.laser_disc_player'=>['nullable','boolean'],
                'attributes.satellite_radio'=>['nullable','boolean'],
                'attributes.premium_sound_system'=>['nullable','boolean'],
                'attributes.long_route_equipment'=>['nullable','boolean'],
                'attributes.bluetooth_system'=>['nullable','boolean'],
                'attributes.parking_sensors'=>['nullable','boolean'],
                'attributes.rear_camera'=>['nullable','boolean'],
                'attributes.special-coating'=>['nullable','boolean'],
                'attributes.brake'=>['nullable','boolean'],
                'attributes.climate-monitoring'=>['nullable','boolean'],

            ],

            in_array($category->name, ['Hyper', 'Super_motorcycle','super_sports','Adventure_Touring','Chopper_Motorcycle','Off-road Capability',
                'Scooter','Vintage','Cafe_Racer','Dual-sport_Bike','Trailer','Karting','Moped','Hoverboard'
            ]) => [
                'attributes.title' => ['required'],
                'attributes.address' => ['required'],

                'attributes.phone'=>['required'],
                'attributes.price'=>['required'],
                'attributes.description'=>['required'],
                'attributes.usage'=>['required'],
                'attributes.mileage'=>['required'],
                'attributes.seller_type'=>['required'],
                'attributes.range_of_price'=>['required'],
                'attributes.warranty'=>['required','boolean'],
                'attributes.transmission'=>['required'],
                'attributes.tires'=>['required'],
                'attributes.manufacturer'=>['required'],
                'attributes.engine_size'=>['required'],
                'attributes.location'=>['nullable'],
                'attributes.street_name'=>['nullable'],

            ],

            in_array($category->name, [
                'Air-Conditioning-Heating-Parts',
                'Batteries',
                'Brakes',
                'Engine-Computer-Parts',
                'Exhaust-Pipe/Air-Conditioning',
                'Exterior-Parts',
                'Interior-Parts',
                'Lighting',
                'Suspension-System',
                'Wheels/Tires',
                'Decorations',
                'Boat-Accessories',
                'Car-4x4-Accessories',
                'Goods',//سلع
                'Motorcycle-Accessories',
                'supplies',
                'Body-Frame',
                'Brakes-Suspension',
                'Engines-Components',
                'Tool-Accessories',
                'Tool-Kit',//طقم ادوات
                'Tools',
                'Accessories-and-spare-parts',//اكسسوالرات و قطع غيار
                'Electronic-Spare-Parts',
                'Engine-Spare-Parts',
                'Swimming-and-Diving-Equipment',
                'Sailing-Equipment',

            ]) => [
            'attributes.title' => ['required'],
            'attributes.price' => ['required'],
            'attributes.description' => ['required'],
            'attributes.phone' => ['required'],
            'attributes.usage' => ['required'],
            'attributes.condition' => ['required'],
            'attributes.seller_type' => ['required'],
            'attributes.location' => ['nullable'],
            'attributes.sail_boats' => ['nullable','boolean'],
            'attributes.motor_internal_boats' => ['nullable','boolean'],
            'attributes.all_boat_types' => ['nullable','boolean'],
            'attributes.motor_external_boats' => ['nullable','boolean'],
            'attributes.other_boat_types' => ['nullable','boolean'],
            'attributes.oar_boats' => ['nullable','boolean'],

        ],

            default => [],
        };

        return array_merge($commonRules, $categoryRules);
    }
}
