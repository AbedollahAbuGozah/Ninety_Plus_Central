<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $instructors = Instructor::query()->pluck('id')->toArray();
        $modules = Module::query()->pluck('id')->toArray();
        return [
            'title' => fake()->title(),
            'instructor_id' => fake()->randomElement($instructors),
            'period' => fake()->randomElement(['first', 'second', 'third']),
            'module_id' => fake()->randomElement($modules),
        ];
    }
}
