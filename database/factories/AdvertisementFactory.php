<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'city_id' =>  City::inRandomOrder()->first()->id,
            'category_id' =>  Category::inRandomOrder()->first()->id,
            'is_special' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'expire_data' => Carbon::now()->addDays(30),
        ];
    }
}
