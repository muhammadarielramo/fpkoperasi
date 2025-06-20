<?php

namespace Database\Factories;

use App\Models\CashFlow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\cashflow>
 */
class cashflowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CashFlow::class;

    public function definition()
    {
        // Random boolean untuk kas masuk / keluar
        $isCashIn = $this->faker->boolean();

        return [
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'nominal' => $this->faker->numberBetween(10000, 1000000),
            'is_cash_in' => $isCashIn,
            'type' => $this->faker->randomElement([
                'operational',
                'misc_income',
                'misc_expense',
                'transfer',
                'adjustment'
            ]),
            'description' => $this->faker->sentence(4),
            'created_by' => User::inRandomOrder()->first()->id ?? User::factory(), // fallback ke factory user
            'related_transaction_id' => null, // atau Transaction::inRandomOrder()->first()->id
        ];
        }
    }
