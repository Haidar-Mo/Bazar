<?php

namespace Database\Factories;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdvertisementAttribute>
 */
class AdvertisementAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'advertisement_id' => Advertisement::factory(),
            'title' => $this->faker->word(),
            'name' => $this->faker->name,
            'value' => $this->faker->sentence()
        ];
    }
}
