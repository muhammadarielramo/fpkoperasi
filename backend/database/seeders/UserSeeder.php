<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::insert([
        //     'email' => 'admin@example.com',
        //     'email_verified_at' => now(),
        //     'kode_otp' => null,
        //     'password' => Hash::make('password123'), // Gantilah dengan password yang aman
        //     'username' => 'adminuser',
        //     'name' => 'Admin Koperasi',
        //     'phone_number' => '081234567890',
        //     'id_role' => 1, // Pastikan role dengan ID 1 sudah ada di tabel roles
        //     'is_active' => true,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        User::insert([
            'email' => 'member1@example.com',
            'email_verified_at' => now(),
            'kode_otp' => null,
            'password' => Hash::make('password123'), // Gantilah dengan password yang aman
            'username' => 'adminuser',
            'name' => 'Admin Koperasi',
            'phone_number' => '081234567890',
            'id_role' => 3, // Pastikan role dengan ID 1 sudah ada di tabel roles
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::insert([
            'email' => 'kolektor1@example.com',
            'email_verified_at' => now(),
            'kode_otp' => null,
            'password' => Hash::make('password123'), // Gantilah dengan password yang aman
            'username' => 'adminuser',
            'name' => 'Admin Koperasi',
            'phone_number' => '081234567890',
            'id_role' => 2, // Pastikan role dengan ID 1 sudah ada di tabel roles
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


    }
}
