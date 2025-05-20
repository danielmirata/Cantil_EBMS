<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = new User();
$user->name = 'Test Secretary';
$user->email = 'secretary@test.com';
$user->password = Hash::make('password123');
$user->role = 'secretary';
$user->save();

echo "Test secretary user created successfully.\n";
