<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Deposit::class;
    public function definition(): array
    {
        return [
            'id_member' => Member::factory(),
            'jenis_simpanan' => fake()->randomElement(['wajib', 'sukarela', 'pokok']),
            'total_simpanan' => fake()->randomNumber(),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
