<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SecretaryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [ 'email' => 'secretary@cantil-e.test' ],
            [
                'fullname' => 'Barangay Secretary',
                'username' => 'secretary',
                'email' => 'secretary@cantil-e.test',
                'account_type' => 'secretary',
                'password' => Hash::make('12345678'),
            ]
        );
    }
} 