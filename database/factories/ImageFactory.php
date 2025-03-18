<?php

namespace Database\Factories;

use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imageable_id' => Advertisement::inRandomOrder()->first()->id,
            'imageable_type' => Advertisement::class,
            'path' => 'ads/adImage.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
