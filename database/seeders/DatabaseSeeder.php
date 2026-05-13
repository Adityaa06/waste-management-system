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
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Worker
        \App\Models\User::factory()->create([
            'name' => 'Worker User',
            'email' => 'worker@example.com',
            'role' => 'worker',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Regular User
        \App\Models\User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
