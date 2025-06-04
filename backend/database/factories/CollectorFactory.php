<?php

namespace Database\Factories;

use App\Models\Collector;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collector>
 */
class CollectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Collector::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'status' => fake()->randomElement(['Aktif', 'Nonaktif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
