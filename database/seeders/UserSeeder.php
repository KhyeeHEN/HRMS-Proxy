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
        // Manager Users
        User::create([
            'username' => 'manager.one',
            'name' => 'Manager Alpha',
            'email' => 'manager.alpha@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'access' => 'Manager',
        ]);

        User::create([
            'username' => 'manager.two',
            'name' => 'Manager Beta',
            'email' => 'manager.beta@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'access' => 'Manager',
        ]);

        // Staff Users
        User::create([
            'username' => 'staff.one',
            'name' => 'Staff A',
            'email' => 'staff.a@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'access' => 'Employee',
        ]);

        User::create([
            'username' => 'staff.two',
            'name' => 'Staff B',
            'email' => 'staff.b@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'access' => 'Employee',
        ]);

        User::create([
            'username' => 'staff.three',
            'name' => 'Staff C',
            'email' => 'staff.c@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'access' => 'Employee',
        ]);
    }
}
