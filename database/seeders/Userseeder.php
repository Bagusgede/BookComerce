<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or Update Admin User
        User::firstOrCreate(
            ['email' => 'AlitAsmara@gmail.com'],
            [
                'name' => 'Alit Asmara',
                'password' => Hash::make('Alit123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'city' => 'Jakarta',
                'postal_code' => '12345',
                'email_verified_at' => now(),
            ]
        );

        // Create or Update Sample User
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '082345678901',
                'address' => 'Jl. User No. 2',
                'city' => 'Bandung',
                'postal_code' => '40123',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ User seeder completed: 1 admin, 1 user created/updated');
    }
}
