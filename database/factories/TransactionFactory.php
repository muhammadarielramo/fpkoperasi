<?php

namespace Database\Factories;

use App\Models\Collector;
use App\Models\Deposit;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Transaction::class;
    public function definition(): array
    {
        return [
            'id_collector' => Collector::factory(),
            'id_anggota' => Member::factory(),
            'id_loan' => Loan::factory(),
            'id_installment' => Installment::factory(),
            'id_deposit' => Deposit::factory(),
            'tipe_transaksi' => $this->faker->randomElement(['debit', 'kredit']),
            'tgl_transaksi' => now(),
            'jumlah' => fake()->randomFloat(2, 0, 1000),
            'keterangan' => fake()->sentence(),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
