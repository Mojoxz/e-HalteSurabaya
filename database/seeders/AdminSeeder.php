<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@dishub.com')->first();

        if (!$adminExists) {
            // Buat admin default
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@dishub.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(), // Optional: langsung verify email
            ]);

            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists.');
        }

        // Tampilkan info login
        $this->command->info('=================================');
        $this->command->info('Admin Login Credentials:');
        $this->command->info('Email: admin@halte.com');
        $this->command->info('Password: password');
        $this->command->info('=================================');
    }
}
