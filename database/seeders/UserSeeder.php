<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the admin and demo customer accounts.
     *
     * Uses updateOrCreate keyed on email, so running this seeder again
     * updates the same records instead of creating duplicates.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nova-ecommerce.test'],
            [
                'name' => 'Nova Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $customers = [
            ['name' => 'Sarah Bennett', 'email' => 'sarah.bennett@example.com'],
            ['name' => 'Marcus Ilyas', 'email' => 'marcus.ilyas@example.com'],
            ['name' => 'Yuki Tanaka', 'email' => 'yuki.tanaka@example.com'],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
