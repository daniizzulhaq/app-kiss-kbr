<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User BPDAS
        User::create([
            'name' => 'Admin BPDAS',
            'email' => 'bpdas@example.com',
            'password' => Hash::make('password'),
            'role' => 'bpdas',
        ]);

        // User Kelompok
        User::create([
            'name' => 'Kelompok Tani',
            'email' => 'kelompok@example.com',
            'password' => Hash::make('password'),
            'role' => 'kelompok',
        ]);
    }
}