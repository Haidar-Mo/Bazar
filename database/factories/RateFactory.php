<?php

namespace Database\Factories;

use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Unique;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rate>
 */
class RateFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'rated_user_id' => User::factory(),
            'rate' => $this->faker->randomFloat(1, 0.0, 5.0),
            'comment' => $this->faker->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Rate $rate) {
            $rate->user_id = User::inRandomOrder()->where('id', '!=', $rate->ratedUser()->id)->first()->id;
        });
    }
}

