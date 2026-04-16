<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseUiVolumeSeeder extends Seeder
{
    public function run(): void
    {
        $finance = User::where('email', 'finance@erp.test')->first();

        if (Supplier::count() < 50) {
            Supplier::factory()->count(50 - Supplier::count())->create();
        }

        Expense::factory()->count(300)->create([
            'requested_by' => $finance?->id,
        ]);

        Expense::factory()->forecast()->count(60)->create([
            'requested_by' => $finance?->id,
        ]);

        Expense::factory()->overdue()->count(40)->create([
            'requested_by' => $finance?->id,
        ]);
    }
}