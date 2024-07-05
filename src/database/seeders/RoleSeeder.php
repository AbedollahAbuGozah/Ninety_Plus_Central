<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'can_register' => false],
            ['name' => 'hr', 'can_register' => false],
            ['name' => 'instructor', 'can_register' => true],
            ['name' => 'student', 'can_register' => true],
        ]);
    }
}
