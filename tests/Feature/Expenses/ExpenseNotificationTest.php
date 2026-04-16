<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;
use App\Notifications\ExpenseSubmittedNotification;

class ExpenseNotificationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_submit_for_approval_sends_notification_to_validator(): void
    {
        Notification::fake();

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
        ]);

        $this->actingAs($finance)->post(route('expenses.submit-for-approval', $expense));

        Notification::assertSentTo(
            [$validator],
            ExpenseSubmittedNotification::class
        );
    }
}