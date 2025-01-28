<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Export>
 */
class ExportFactory extends Factory
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
            'file_path' => fake()->word() . '.csv',
            'format' => fake()->randomElement(['csv', 'xlsx']),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'failed']),
            'progress' => fake()->numberBetween(0, 100),
            'total' => fake()->numberBetween(0, 100),
        ];
    }
}
