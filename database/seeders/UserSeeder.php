<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'username' => 'user' . $i,
                'name' => 'User Name Example ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'access' => ($i % 2 == 0) ? 'Admin' : 'HR',
            ]);
        }
    }
}
