<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating default users...');

        // Create Super Admin (ID = 1)
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@halte.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08123456789',
            'address' => 'Surabaya, Jawa Timur, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create additional admin
        User::create([
            'name' => 'Admin Operator',
            'email' => 'operator@halte.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08123456788',
            'address' => 'Surabaya, Jawa Timur, Indonesia',
            'is_active' => true,
        ]);

    }
}
