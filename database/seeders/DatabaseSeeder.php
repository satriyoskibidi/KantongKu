<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Kategori;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{



    public function run(): void
    {

        Pengguna::firstOrCreate(
            ['email' => 'admin@kantongku.test'],
            ['name' => 'Admin KantongKu', 'password' => 'password123', 'role' => 'admin'],
        );

        Pengguna::firstOrCreate(
            ['email' => 'mahasiswa@kantongku.test'],
            ['name' => 'Mahasiswa Demo', 'password' => 'password123', 'role' => 'mahasiswa'],
        );

        foreach (['Uang Saku', 'Beasiswa', 'Pekerjaan', 'Usaha', 'Hadiah', 'Lainnya'] as $name) {
            Kategori::firstOrCreate(['name' => $name, 'type' => 'income']);
        }

        foreach (['Makan', 'Transport', 'Kuota', 'Kos', 'Kuliah', 'Belanja', 'Hiburan', 'Kesehatan', 'Lainnya'] as $name) {
            Kategori::firstOrCreate(['name' => $name, 'type' => 'expense']);
        }
    }
}
