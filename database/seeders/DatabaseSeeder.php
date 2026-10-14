<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::firstOrCreate(
            ['email' => 'admin@kantongku.test'],
            ['name' => 'Admin KantongKu', 'password' => 'password123', 'role' => 'admin'],
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@kantongku.test'],
            ['name' => 'Mahasiswa Demo', 'password' => 'password123', 'role' => 'mahasiswa'],
        );
    }
}
