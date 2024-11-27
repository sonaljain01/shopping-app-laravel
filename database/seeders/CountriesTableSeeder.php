<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'India', 'dial_code' => '+91'],
            ['name' => 'USA', 'dial_code' => '+1'],
            ['name' => 'UK', 'dial_code' => '+44'],
            ['name' => 'China', 'dial_code' => '+86'],
            ['name' => 'Europe', 'dial_code' => '+49'],
        ];

        DB::table('countries')->insert($countries);
    }
}
