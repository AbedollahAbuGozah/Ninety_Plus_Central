<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Module;
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
        $ret = [
            'title' => fake()->title(),
            'instructor_id' => fake()->randomElement($instructors),
            'period' => fake()->randomElement(['first', 'second', 'third']),
            'module_id' => fake()->randomElement($modules),
            'description' => fake()->text(),
            'properties' => [
                "cover_image" => fake()->url(),
                "weekly_lectures" => fake()->numberBetween(1, 10),
                "intro_video" => fake()->url(),
                "welcome_message" => fake()->text(),
                "ending_message" => fake()->text()
            ]
        ];
        logger($ret);
        return $ret;
    }
}
