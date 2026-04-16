<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;

class ExpenseAccessTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_admin_can_access_expenses_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('expenses.index'));

        $response->assertOk();
    }

    public function test_finance_can_access_expenses_index(): void
    {
        $finance = $this->createFinance();

        $response = $this->actingAs($finance)->get(route('expenses.index'));

        $response->assertOk();
    }

    public function test_validator_can_access_expenses_index(): void
    {
        $validator = $this->createValidator();

        $response = $this->actingAs($validator)->get(route('expenses.index'));

        $response->assertOk();
    }

    public function test_reader_can_access_expenses_index(): void
    {
        $reader = $this->createReader();

        $response = $this->actingAs($reader)->get(route('expenses.index'));

        $response->assertOk();
    }

    public function test_finance_can_open_expense_show_page(): void
    {
        $finance = $this->createFinance();
        $expense = Expense::factory()->create();

        $response = $this->actingAs($finance)->get(route('expenses.show', $expense));

        $response->assertOk();
    }

    public function test_reader_cannot_access_expense_create_page_if_policy_blocks_creation(): void
    {
        $reader = $this->createReader();

        $response = $this->actingAs($reader)->get(route('expenses.create'));

        $response->assertForbidden();
    }

    public function test_finance_can_access_expense_create_page(): void
    {
        $finance = $this->createFinance();

        $response = $this->actingAs($finance)->get(route('expenses.create'));

        $response->assertOk();
    }
}