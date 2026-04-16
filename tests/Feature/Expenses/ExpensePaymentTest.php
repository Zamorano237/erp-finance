<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;

class ExpensePaymentTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_finance_can_register_payment_on_non_allocated_expense(): void
    {
        $finance = $this->createFinance();

        $expense = Expense::factory()->create([
            'is_allocated' => false,
            'amount_ttc' => 1000,
            'amount_paid' => 0,
            'balance_due' => 1000,
            'status' => 'waiting_payment',
        ]);

        $response = $this->actingAs($finance)->post(route('expenses.payments.store', $expense), [
            'amount' => 400,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Virement',
            'reference' => 'PAY-TEST-001',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('payments', [
            'expense_id' => $expense->id,
            'amount' => 400,
            'reference' => 'PAY-TEST-001',
        ]);
    }

    public function test_reader_cannot_register_payment(): void
    {
        $reader = $this->createReader();

        $expense = Expense::factory()->create([
            'is_allocated' => false,
            'status' => 'waiting_payment',
        ]);

        $response = $this->actingAs($reader)->post(route('expenses.payments.store', $expense), [
            'amount' => 300,
            'payment_date' => now()->toDateString(),
        ]);

        $response->assertForbidden();
    }
}