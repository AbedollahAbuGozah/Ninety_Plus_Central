<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Cv;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\UserRole;
use Database\Factories\UserRoleFactory;
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

    }
}
