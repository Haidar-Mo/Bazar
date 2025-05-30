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
            'user_id' => User::factory(),
            'city_id' => City::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $this->faker->word(),
            'price' => $this->faker->randomNumber(4),
            'type' => $this->faker->randomElement(['عرض', 'طلب']),
            'currency_type' => $this->faker->randomElement(['ليرة', 'دولار']),
            'negotiable' => $this->faker->boolean(),
            'is_special' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'expiry_date' => Carbon::now()->addDays(30),
        ];
    }
}
