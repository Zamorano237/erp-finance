<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;

class ExpenseApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_finance_can_submit_expense_for_approval(): void
    {
        $finance = $this->createFinance();
        $validator = $this->createValidator();

        $expense = Expense::factory()->create([
            'requested_by' => $finance->id,
            'requires_approval' => true,
            'validation_status' => 'not_submitted',
            'status' => 'open',
        ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $expense->id,
            'approver_id' => $validator->id,
            'created_by' => $finance->id,
            'status' => 'pending',
            'requested_at' => null,
            'decided_at' => null,
        ]);

        $response = $this->actingAs($finance)
            ->post(route('expenses.submit-for-approval', $expense));

        $response->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'validation_status' => 'pending',
            'status' => 'in_validation',
        ]);
    }

    public function test_validator_can_approve_expense_assigned_to_him(): void
    {
        $finance = $this->createFinance();
        $validator = $this->createValidator();

        $expense = Expense::factory()->create([
            'requested_by' => $finance->id,
            'requires_approval' => true,
            'validation_status' => 'pending',
            'status' => 'in_validation',
        ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $expense->id,
            'approver_id' => $validator->id,
            'created_by' => $finance->id,
            'status' => 'pending',
            'requested_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($validator)->post(route('expenses.approve', $expense), [
            'comment' => 'Validation de test',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'validation_status' => 'approved',
            'status' => 'waiting_payment',
            'validated_by' => $validator->id,
        ]);
    }

    public function test_validator_can_reject_expense_assigned_to_him(): void
    {
        $finance = $this->createFinance();
        $validator = $this->createValidator();

        $expense = Expense::factory()->create([
            'requested_by' => $finance->id,
            'requires_approval' => true,
            'validation_status' => 'pending',
            'status' => 'in_validation',
        ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $expense->id,
            'approver_id' => $validator->id,
            'created_by' => $finance->id,
            'status' => 'pending',
            'requested_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($validator)->post(route('expenses.reject', $expense), [
            'comment' => 'Rejet de test',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'validation_status' => 'rejected',
            'status' => 'rejected',
            'validated_by' => $validator->id,
        ]);
    }

    public function test_reader_cannot_approve_expense(): void
    {
        $reader = $this->createReader();
        $expense = Expense::factory()->create([
            'requires_approval' => true,
            'validation_status' => 'pending',
            'status' => 'in_validation',
        ]);

        $response = $this->actingAs($reader)->post(route('expenses.approve', $expense), [
            'comment' => 'Tentative interdite',
        ]);

        $response->assertForbidden();
    }
}