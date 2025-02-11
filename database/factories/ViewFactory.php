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
        do {
            $user_id = User::inRandomOrder()->first()->id ;
            $advertisement_id = Advertisement::inRandomOrder()->first()->id;
        } while (View::where('user_id', $user_id)->where('advertisement_id', $advertisement_id)->exists()); 

        return [
            'user_id' => $user_id,
            'advertisement_id' => $advertisement_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
