<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;

class ExpenseValidationCenterTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_validator_sees_only_his_pending_approvals(): void
    {
        $validator = $this->createValidator();
        $otherValidator = $this->createValidator();

        $expenseForValidator = Expense::factory()->create([
            'label' => 'Dépense visible',
        ]);

        $expenseForOther = Expense::factory()->create([
            'label' => 'Dépense non visible',
        ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $expenseForValidator->id,
            'approver_id' => $validator->id,
            'status' => 'pending',
        ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $expenseForOther->id,
            'approver_id' => $otherValidator->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($validator)
            ->get(route('expenses.validation-center'));

        $response->assertOk();
        $response->assertSee('Dépense visible');
        $response->assertDontSee('Dépense non visible');
    }
}