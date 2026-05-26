<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Worker
        \App\Models\User::firstOrCreate(
            ['email' => 'worker@example.com'],
            [
                'name' => 'Worker User',
                'role' => 'worker',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Regular User
        \App\Models\User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'role' => 'user',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
    }
}
