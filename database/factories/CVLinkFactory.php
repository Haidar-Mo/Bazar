<?php

namespace Database\Factories;

use App\Models\CV;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CVLink>
 */
class CVLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cv_id' => CV::inRandomOrder()->first()->id,
            'name' => $this->faker->randomElement(['LinkedIn', 'GitHub', 'Portfolio', 'Personal Website']),
            'link' =>  $this->faker->url,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
