<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cv>
 */
class CvFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => fake()->filePath(),
            'is_verified' => rand(1,0),
            'is_accepted' => rand(1, 0),
            'instructor_id' => $this->faker->unique()->numberBetween(1, 5),

        ];
    }
}
