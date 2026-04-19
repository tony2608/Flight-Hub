<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Admin (Ganti Foto & Destinasi)
        User::updateOrCreate(
            ['email' => 'admin1@gmail.com'],
            [
                'name' => 'Pak Admin',
                'password' => Hash::make('123456'),
                'role' => 'admin'
            ]
        );

        // 2. Akun Manager (Lihat Grafik Keuangan)
        User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('123456'),
                'role' => 'manager'
            ]
        );

        // 3. Akun Staff (Kelola Penerbangan & Bandara)
        User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('123456'),
                'role' => 'staff'
            ]
        );

        // 4. Akun HRD (Kelola Akun & Kompensasi Batal)
        User::updateOrCreate(
            ['email' => 'hrd@gmail.com'],
            [
                'name' => 'staff HRD',
                'password' => Hash::make('123456'),
                'role' => 'hrd'
            ]
        );

        // 5. Akun Pembeli Biasa
        User::updateOrCreate(
            ['email' => 'tony@gmail.com'],
            [
                'name' => 'Fathoni',
                'password' => Hash::make('123456'),
                'role' => 'user'
            ]
        );
    }
}