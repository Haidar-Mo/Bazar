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
        $ratedUser = User::inRandomOrder()
            ->where('id', '!=', $user->id)
            ->first();
        while (
            Rate::where('rated_user_id', $ratedUser->id)
                ->where('user_id', $user->id)
                ->exists()
        ) {
            $user = User::inRandomOrder()
                ->where('id', '!=', $user->id)
                ->where('id', '!=', $ratedUser->id)
                ->first();
        }
        return [
            'user_id' => $user->id,
            'rated_user_id' => $ratedUser->id,
            'rate' => $this->faker->randomFloat(1, 0.0, 5.0),
            'comment' => $this->faker->sentence(),
        ];
    }
}

