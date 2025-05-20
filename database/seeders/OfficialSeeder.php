<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficialSeeder extends Seeder
{
    public function run()
    {
        // Get the first position (Barangay Captain)
        $positionId = DB::table('positions')->where('position_name', 'Barangay Captain')->first()->id;

        DB::table('officials')->insert([
            [
                'position_id' => $positionId,
                'first_name' => 'John',
                'middle_name' => 'Doe',
                'last_name' => 'Smith',
                'suffix' => null,
                'date_of_birth' => '1980-01-01',
                'place_of_birth' => 'Manila',
                'gender' => 'Male',
                'civil_status' => 'Married',
                'nationality' => 'Filipino',
                'religion' => 'Catholic',
                'email' => 'john.smith@example.com',
                'contact_number' => '09123456789',
                'house_number' => '123',
                'street' => 'Main Street',
                'barangay' => 'Cantil-E',
                'municipality' => 'Davao City',
                'zip' => '8000',
                'father_name' => 'James Smith',
                'mother_name' => 'Mary Smith',
                'guardian_name' => 'James Smith',
                'guardian_contact' => '09123456789',
                'guardian_relation' => 'Parent',
                'term_start' => '2023-01-01',
                'term_end' => '2025-12-31',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 