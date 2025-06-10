<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\User;
use App\Models\View;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\View>
 */
class ViewFactory extends Factory
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
            'advertisement_id' => Advertisement::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
