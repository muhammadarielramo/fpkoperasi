<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nik' => fake()->randomNumber(),
            'address' => fake()->address(),
            'is_verified' => fake()->boolean(),
            'bod' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female']),
            'created_at' => now(),
            'updated_at'  => now(),
        ];
    }
}
