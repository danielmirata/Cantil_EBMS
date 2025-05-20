<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Household;

class HouseholdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Household::create([
            'name' => 'Test Household',
            'house_number' => '123',
            'street' => 'Test Street',
            'barangay' => 'Cantil-E',
            'municipality' => 'Test Municipality',
            'zip_code' => '1234'
        ]);
    }
}
