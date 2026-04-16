<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseApprovalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'expense_id' => Expense::factory(),
            'approver_id' => User::factory(),
            'status' => 'pending',
            'approval_order' => 1,
            'requested_at' => now()->subDays(2),
            'decided_at' => null,
            'comment' => null,
            'created_by' => User::factory(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => 'approved',
            'decided_at' => now()->subDay(),
            'comment' => 'Validation effectuée.',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => 'rejected',
            'decided_at' => now()->subDay(),
            'comment' => 'Rejet pour pièce justificative manquante.',
        ]);
    }
}