<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'address' => $this->faker->address(),
            'job' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'gender' => 'male',
            'description' => $this->faker->sentences(4, true),
            'is_verified' => $this->faker->boolean(0),
            'email_verified_at' => $this->faker->optional(0)->dateTime(),
            'remember_token' => Str::random(10),
            'device_token' => Str::random(50)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function isHaidar(): static
    {
        return $this->state(fn(array $attributes) => [
            'first_name' => 'mohammad',
            'last_name' => 'Haidar',
            'email' => 'mohammad44.haidar@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '0936287134',
            'gender' => 'male',
            'job' => 'IT job',
            'company' => 'Haidar Company',
            'description' => 'a junior Laravel developer',
            'address' => 'Yabroud',
            'provider' => null,
            'provider_id' => null,
            'is_verified' => true,
            'device_token' => 'eu26jBhbREWOqjl8Js22dS:APA91bFj-oif-QpSgN0RFE5fuDQwIPdc1uLGiMidPkvMp3wUca85loQvB0TVNi5SvBkVrZNcTwj_9XQzFC1w79a7AxugdFQp4RaDcmydH3OC3Ce5unodVww',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(Role::where('name', 'client')->where('guard_name', 'api')->firstOrFail());
        });
    }
}
