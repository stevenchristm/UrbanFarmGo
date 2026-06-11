<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user if it does not already exist
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'admin',
                'password' => Hash::make('admin123'),
                // Add any admin-specific fields here, e.g., 'is_admin' => true
            ]
        );
    }
}
