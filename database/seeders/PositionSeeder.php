<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run()
    {
        DB::table('positions')->insert([
            [
                'position_name' => 'Barangay Captain',
                'description' => 'Head of the Barangay',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'position_name' => 'Barangay Secretary',
                'description' => 'Handles administrative tasks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'position_name' => 'Barangay Treasurer',
                'description' => 'Handles financial matters',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 