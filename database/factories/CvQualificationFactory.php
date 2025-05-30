<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cv>
 */
class CvQualificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cv_id' => Cv::factory(),
            'certificate' => $this->faker->randomElement(['Bachelor\'s', 'Master\'s', 'PhD']),
            'specialization' => $this->faker->word,
            'university' => $this->faker->company,
            'country' => $this->faker->country,
            'entering_date' => $this->faker->date(),
            'graduation_date' => $this->faker->optional()->date(), // Nullable
            'still_studying' => $this->faker->boolean,
        ];
    }
}
