<?php

namespace Database\Factories;

use App\Enums\StatusTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'registered_at' => fake()->date(),
            'status' => $this->faker->randomElement(StatusTypes::cases())->value,
            'city' => fake()->city(),
        ];
    }
}
