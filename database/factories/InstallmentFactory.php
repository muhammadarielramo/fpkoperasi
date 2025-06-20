<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Installment>
 */
class InstallmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Installment::class;
    public function definition(): array
    {
        return [
            'id_loan' => Loan::factory(),
            'tgl_pembayaran' => fake()->date(),
            'cicilan_ke' => fake()->randomNumber(1),
            'besar_ciclan' => fake()->randomNumber(6),
            'status' => fake()->randomElement(['Lunas', 'Belum Lunas']),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
