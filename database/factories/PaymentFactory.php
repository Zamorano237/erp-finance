<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'expense_id' => Expense::factory(),
            'expense_allocation_id' => null,
            'amount' => fake()->randomFloat(2, 50, 1500),
            'payment_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'payment_method' => fake()->randomElement(['Virement', 'CB', 'Prélèvement']),
            'reference' => 'PAY-' . fake()->numerify('######'),
            'comment' => fake()->optional()->sentence(),
            'paid_by' => User::factory(),
        ];
    }
}