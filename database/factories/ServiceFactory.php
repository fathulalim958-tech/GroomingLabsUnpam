<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Classic Cut', 'Skin Fade', 'Beard Sculpt']),
            'description' => fake()->sentence(),
            'duration_minutes' => fake()->randomElement([30, 45, 60]),
            'price' => fake()->randomElement([45000, 65000, 85000]),
            'is_active' => true,
        ];
    }
}
