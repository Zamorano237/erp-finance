<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseAllocationFactory extends Factory
{
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 50, 1200);

        return [
            'expense_id' => Expense::factory(),
            'allocation_number' => fake()->numberBetween(1, 12),
            'label' => fake()->sentence(3),
            'start_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+3 months'),
            'amount' => $amount,
            'amount_paid' => 0,
            'balance_due' => $amount,
            'status' => 'to_pay',
            'planned_payment_date' => fake()->dateTimeBetween('now', '+3 months'),
            'payment_date' => null,
            'payment_mode' => fake()->randomElement(['Virement', 'Prélèvement']),
            'is_locked' => false,
            'locked_at' => null,
            'locked_by' => null,
            'meta' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $amount = (float) $attributes['amount'];

            return [
                'amount_paid' => $amount,
                'balance_due' => 0,
                'status' => 'paid',
                'payment_date' => now()->subDays(2),
                'is_locked' => true,
                'locked_at' => now()->subDays(2),
            ];
        });
    }

    public function partial(): static
    {
        return $this->state(function (array $attributes) {
            $amount = (float) $attributes['amount'];
            $paid = round($amount * 0.5, 2);

            return [
                'amount_paid' => $paid,
                'balance_due' => round($amount - $paid, 2),
                'status' => 'partially_paid',
            ];
        });
    }
}