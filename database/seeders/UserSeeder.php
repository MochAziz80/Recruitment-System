<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrator
        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'), // password
            'role' => 'Administrator',
        ]);

        // Applicant
        User::create([
            'id' => Str::uuid(),
            'name' => 'Applicant User',
            'email' => 'applicant@example.com',
            'password' => Hash::make('aplicant'),
            'role' => 'Applicant',
        ]);
    }
}
