<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\constants\RoleOptions;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use App\Models\Cv;
use App\Models\UserRole;
use Database\Factories\ChapterFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            BranchSeeder::class,
            RoleSeeder::class,
            ResourceSeeder::class,
            CitySeeder::class,
        ]);

        $studentId = DB::table('users')->insertGetId([
            'first_name' => 'abed',
            'last_name' => 'abugozah',
            'city_id' => 1,
            'phone' => '0592910694',
            'birth_date' => '2002-05-31',
            'gender' => 1,
            'branch_id' => 1,
            'password' => Hash::make('1233211'),
            'email' => 'abedullah@student.com',
        ]);

        $instructorId = DB::table('users')->insertGetId([
            'first_name' => 'imam',
            'last_name' => 'droubi',
            'city_id' => 1,
            'phone' => '0595663344',
            'birth_date' => '2002-05-31',
            'gender' => 1,
            'about' => 'bla bla blaaaaaaaaa',
            'password' => Hash::make('1233211'),
            'email' => 'imam@instructor.com',
        ]);

        DB::table('role_user')->insert([
            [
                'user_id' => $studentId,
                'role_id' => Role::where('name', RoleOptions::STUDENT)->first()->id,
            ],
            [
                'user_id' => $instructorId,
                'role_id' => Role::where('name', RoleOptions::INSTRUCTOR)->first()->id,
            ]
        ]);

        session('uid', '2');

        User::factory(9)->create();
        Cv::factory(5)->create();
        UserRole::factory(10)->create();
        Module::factory(10)->create();
        Course::factory(30)->create();
        CourseStudent::factory(10)->create();
        Chapter::factory(60)->create();

    }
}
