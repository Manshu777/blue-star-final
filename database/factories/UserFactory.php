<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $username = fake()->unique()->userName();

        return [
            'username' => $username,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password
            'remember_token' => Str::random(10),
            'role' => 'user',
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the user is a photographer.
     */
    public function photographer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'photographer',
            'username' => fake()->unique()->userName(),
        ]);
    }
}
