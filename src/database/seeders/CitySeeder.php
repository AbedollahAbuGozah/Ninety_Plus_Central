<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            ['name' => 'Palestine'],
        ]);
        DB::table('cities')->insert([
            ['name' => 'Tulkarem', 'country_id' => 1],
            ['name' => 'Nablus', 'country_id' => 1],
            ['name' => 'Ramallah', 'country_id' => 1],
            ['name' => 'Jenen', 'country_id' => 1],
            ['name' => 'Qalqela', 'country_id' => 1],
            ['name' => 'Areha', 'country_id' => 1],
        ]);
    }
}
