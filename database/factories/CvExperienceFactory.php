<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CvExperience>
 */
class CvExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cv_id' => Cv::inRandomOrder()->first()->id, // Create a related Cv record
            'job_name' => $this->faker->jobTitle,
            'job_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Freelance']),
            'company_sector' => $this->faker->randomElement(['IT', 'Finance', 'Education', 'Healthcare', 'Retail']),
            'company_name' => $this->faker->company,
            'country' => $this->faker->country,
            'job_description' => $this->faker->optional()->paragraph, // Nullable
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(), // Nullable
            'current_job' => $this->faker->boolean,
            
        ];
    }
}
