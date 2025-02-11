<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CV>
 */
class CVFactory extends Factory
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
            'cv_path' => 'cvs/cvPdf.pdf', // Simulated file path
            'full_name' => $this->faker->name,
            'summary' => $this->faker->paragraph,
            'image' => 'cvs/cvImage.jpg', // Simulated image path
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->unique()->phoneNumber,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'language' => json_encode([$this->faker->languageCode, $this->faker->languageCode]),
            'nationality' => $this->faker->country,
            'birth_date' => $this->faker->dateTimeBetween('1990-01-01', '2005-01-01'),
            'skills' => $this->faker->sentence(6),
            'education' => $this->faker->paragraph(2),
            'experience' => $this->faker->paragraph(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
