<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Module;
use App\Models\User;
use App\Models\Cv;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([BranchSeeder::class,
            RoleSeeder::class,
            ResourceSeeder::class,
            CitySeeder::class,
        ]);

        User::factory(10)->create();
        Cv::factory(5)->create();
        UserRole::factory(10)->create();
        Module::factory(10)->create();
        Course::factory(30)->create();
        CourseStudent::factory(10)->create();


    }
}
