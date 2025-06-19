<?php

namespace Database\Seeders;

use App\Models\CashFlow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class cashflowseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashFlow::factory()->count(10)->create();
    }
}
