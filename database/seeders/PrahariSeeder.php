<?php

namespace Database\Seeders;

use App\Models\Prahari;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PrahariSeeder extends Seeder
{
    public function run(): void
    {
        // Prahari 1
        $user1 = User::firstOrCreate(
            ['email' => 'prahari1@example.com'],
            [
                'name' => 'Prahari One',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        Prahari::firstOrCreate(
            ['mobile' => '9876543210'],
            [
                'user_id' => $user1->id,
                'name' => 'Prahari One',
                'bank_account' => '123456789012',
                'wallet_balance' => 1500,
                'aadhaar_status' => 'verified',
                'status' => 'active'
            ]
        );

        // Prahari 2
        $user2 = User::firstOrCreate(
            ['email' => 'prahari2@example.com'],
            [
                'name' => 'Prahari Two',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        Prahari::firstOrCreate(
            ['mobile' => '9876543211'],
            [
                'user_id' => $user2->id,
                'name' => 'Prahari Two',
                'bank_account' => '123456789013',
                'wallet_balance' => 800,
                'aadhaar_status' => 'verified',
                'status' => 'active'
            ]
        );
    }
}
