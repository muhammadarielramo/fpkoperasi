<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Loan::class;
    public function definition(): array
    {
        return [
            'id_member' => Member::factory(),
            'tgl_pengajuan' => fake()->date(),
            'jumlah_pinjaman' => fake()->randomNumber(6),
            'tenor' => fake()->randomNumber(1),
            'status' => fake()->randomElement(['Diajukan', 'Diterima', 'Ditolak', 'Lunas']),
            'tgl_persetujuan' => fake()->date(),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
