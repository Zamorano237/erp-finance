<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;

class UserActionCenterTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_finance_can_access_action_center(): void
    {
        $finance = $this->createFinance();

        Expense::factory()->create([
            'status' => 'overdue',
            'label' => 'Dépense en retard test',
        ]);

        Expense::factory()->create([
            'status' => 'waiting_payment',
            'label' => 'Paiement à traiter test',
        ]);

        $response = $this->actingAs($finance)
            ->get(route('action-center.index'));

        $response->assertOk();
        $response->assertSee('Dépense en retard test');
        $response->assertSee('Paiement à traiter test');
    }

    public function test_validator_can_access_action_center_and_see_pending_approvals(): void
    {
        $validator = $this->createValidator();

        $expense = Expense::factory()->create([
            'label' => 'Validation action center',
        ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $expense->id,
            'approver_id' => $validator->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($validator)
            ->get(route('action-center.index'));

        $response->assertOk();
        $response->assertSee('Validation action center');
    }
}