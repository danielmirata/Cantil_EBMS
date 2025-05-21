<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       

        $this->call([
            PositionSeeder::class,
            OfficialSeeder::class,
            // ... other seeders ...
        ]);

        // Create test household
        \App\Models\Household::create([
            'name' => 'Test Household',
            'house_number' => '123',
            'street' => 'Test Street',
            'barangay' => 'Cantil-E',
            'municipality' => 'Test Municipality',
            'zip_code' => '1234'
        ]);

        $this->call(SecretaryUserSeeder::class);
    }
}
