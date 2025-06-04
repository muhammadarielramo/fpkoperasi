<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\User;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            'id_user' => 3,
            'nik' => '1234567890123456',
            'foto_ktp' => 'ktp.jpg', // path/file bisa disesuaikan
            'bod' => '1995-01-01',
            'address' => 'Jl. Contoh Alamat No.123',
            'gender' => 'male',
            'is_verified' => true,
        ]);
    }
}
