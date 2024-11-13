<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\City;
use App\Models\PinCode;
class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path =public_path('indian_cities.csv');  // Adjust path accordingly
        $data = array_map('str_getcsv', file($path));

        foreach ($data as $row) {
            // Assuming CSV columns: state, city, pincode
            // Adjust indices based on CSV structure

            // Create state if not already present
            $state = State::firstOrCreate(['name' => $row[0]]);
            
            // Create city if not already present
            $city = City::firstOrCreate([
                'state_id' => $state->id,
                'name' => $row[1]
            ]);
            
            // Create PIN code for the city
            PinCode::firstOrCreate([
                'city_id' => $city->id,
                'pincode' => $row[2]
            ]);
        }
    
    }
}
