<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branches = Branch::query()->pluck('id')->toArray();
        $countries = Country::query()->pluck('id')->toArray();

        return [
            'name' => fake()->name(),
            'branch_id' => fake()->randomElement($branches),
            'country_id' => fake()->randomElement($countries),
        ];
    }
}
