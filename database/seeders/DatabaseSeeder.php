<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Applications Master Data (6 Core Applications)
        $appsData = [
            ['slug' => 'spontan', 'name' => 'SPONTAN', 'description' => 'Sistem Pelayanan Operasional Terpadu & Administrasi'],
            ['slug' => 'investasi', 'name' => 'Investasi', 'description' => 'Aplikasi Penanaman Modal & Perizinan Investasi Daerah'],
            ['slug' => 'antrian-mpp', 'name' => 'Antrian-MPP', 'description' => 'Sistem Tiket & Antrian Digital Mal Pelayanan Publik'],
            ['slug' => 'mpp', 'name' => 'MPP', 'description' => 'Portal Layanan Terintegrasi Mal Pelayanan Publik'],
            ['slug' => 'sadap', 'name' => 'SADAP', 'description' => 'Sistem Administrasi Data & Arsip Pemerintahan'],
            ['slug' => 'petapotensi', 'name' => 'PetaPotensi', 'description' => 'Sistem Informasi Geografis & Pemetaan Potensi Daerah'],
        ];

        foreach ($appsData as $app) {
            Application::updateOrCreate(
                ['slug' => $app['slug']],
                ['name' => $app['name'], 'description' => $app['description']]
            );
        }

        // 2. Production Accounts for the 3 Roles
        User::updateOrCreate(
            ['email' => 'samsul@it.local'],
            [
                'name' => 'Samsul Bahri',
                'password' => Hash::make('password'),
                'role' => 'programer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'natalia@it.local'],
            [
                'name' => 'Natalia Sarah Pahlevie',
                'password' => Hash::make('password'),
                'role' => 'staff_it',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dehen@it.local'],
            [
                'name' => 'Dehen Nurwin Adam',
                'password' => Hash::make('password'),
                'role' => 'kepala_it',
            ]
        );
    }
}
