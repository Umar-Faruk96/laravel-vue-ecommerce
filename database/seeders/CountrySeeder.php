<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usaStates = [
            "AL" => 'Alabama',
            "AK" => 'Alaska',
            "AZ" => 'Arizona',
            "AR" => 'Arkansas',
            "CA" => 'California',
        ];
        $countries = [
            ['code' => 'geo', 'name' => 'Georgia', 'states' => null],
            ['code' => 'bd', 'name' => 'Bangladesh', 'states' => null],
            ['code' => 'usa', 'name' => 'United States of America', 'states' => json_encode($usaStates)],
            ['code' => 'ger', 'name' => 'Germany', 'states' => null],
        ];
        Country::insert($countries);
    }
}
