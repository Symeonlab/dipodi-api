<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin user account.
     */
    public function run(): void
    {
        // Create main admin account
        User::updateOrCreate(
            ['email' => 'slampada@sylacorp.com'],
            [
                'name' => 'Symeon Lampadarios',
                'email' => 'slampada@sylacorp.com',
                'password' => Hash::make('Nerson007'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: slampada@sylacorp.com');
    }
}
