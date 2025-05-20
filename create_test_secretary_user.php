<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = new User();
$user->fullname = 'Test Secretary';
$user->username = 'testsecretary';
$user->email = 'secretary@test.com';
$user->password = Hash::make('password123');
$user->account_type = 'secretary';
$user->save();

echo "Test secretary user created successfully.\n";
