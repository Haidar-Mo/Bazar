<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomElement = $this->faker->randomElement([
            User::inRandomOrder()->first(),
            Advertisement::inRandomOrder()->first(),
            Rate::where('comment', '!=', null)->inRandomOrder()->first()
        ]);
        return [
            'user_id' => User::factory(),
            'reportable_type' => get_class($randomElement),
            'reportable_id' => $randomElement->id,
            'paragraph' => $this->faker->sentence(),
            'is_read' => $this->faker->boolean(),
        ];
    }
}
