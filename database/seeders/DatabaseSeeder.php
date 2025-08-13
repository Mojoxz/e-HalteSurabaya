<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Halte;
use App\Models\HaltePhoto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@halte.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample user
        User::create([
            'name' => 'User Demo',
            'email' => 'user@halte.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create sample haltes
        $haltes = [
            [
                'name' => 'Halte Surabaya Pusat',
                'description' => 'Halte bus utama di pusat kota Surabaya dengan fasilitas lengkap',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
                'address' => 'Jl. Pemuda No. 1, Surabaya, Jawa Timur',
                'is_rented' => false,
                'simbada_registered' => true,
                'simbada_number' => 'SIMBADA-SBY-001',
                'status' => 'available'
            ],
            [
                'name' => 'Halte Sidoarjo Terminal',
                'description' => 'Halte bus dekat terminal Sidoarjo dengan akses mudah ke berbagai arah',
                'latitude' => -7.4478,
                'longitude' => 112.7183,
                'address' => 'Jl. Gajah Mada No. 10, Sidoarjo, Jawa Timur',
                'is_rented' => true,
                'rent_start_date' => '2025-01-01',
                'rent_end_date' => '2025-12-31',
                'rented_by' => 'PT. Advertising Indonesia',
                'simbada_registered' => true,
                'simbada_number' => 'SIMBADA-SDO-001',
                'status' => 'rented'
            ],
            [
                'name' => 'Halte Waru Junction',
                'description' => 'Halte strategis di persimpangan Waru dengan volume penumpang tinggi',
                'latitude' => -7.3608,
                'longitude' => 112.7319,
                'address' => 'Jl. Raya Waru No. 25, Sidoarjo, Jawa Timur',
                'is_rented' => false,
                'simbada_registered' => true,
                'simbada_number' => 'SIMBADA-WRU-001',
                'status' => 'available'
            ],
            [
                'name' => 'Halte Gedangan Center',
                'description' => 'Halte modern di kawasan bisnis Gedangan',
                'latitude' => -7.3947,
                'longitude' => 112.7272,
                'address' => 'Jl. Raya Gedangan No. 15, Sidoarjo, Jawa Timur',
                'is_rented' => true,
                'rent_start_date' => '2025-06-01',
                'rent_end_date' => '2025-11-30',
                'rented_by' => 'CV. Media Promosi',
                'simbada_registered' => true,
                'simbada_number' => 'SIMBADA-GDG-001',
                'status' => 'rented'
            ],
            [
                'name' => 'Halte Taman Dayu',
                'description' => 'Halte dekat kawasan perumahan dan rekreasi Taman Dayu',
                'latitude' => -7.5169,
                'longitude' => 112.6283,
                'address' => 'Jl. Raya Taman Dayu, Pandaan, Pasuruan, Jawa Timur',
                'is_rented' => false,
                'simbada_registered' => true,
                'simbada_number' => 'SIMBADA-TDY-001',
                'status' => 'available'
            ]
        ];

        foreach ($haltes as $halteData) {
            Halte::create($halteData);
        }

        echo "Database seeded successfully!\n";
        echo "Admin Login:\n";
        echo "Email: admin@halte.com\n";
        echo "Password: password\n";
    }
}
