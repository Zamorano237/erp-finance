<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\User;
use App\Notifications\ExpenseApprovedNotification;
use App\Notifications\ExpenseRejectedNotification;
use App\Notifications\ExpenseSubmittedNotification;

class ExpenseNotificationService
{
    public function notifySubmitted(Expense $expense): void
    {
        $expense->loadMissing('approvals.approver');

        foreach ($expense->approvals as $approval) {
            if ($approval->approver) {
                $approval->approver->notify(new ExpenseSubmittedNotification($expense));
            }
        }
    }

    public function notifyApproved(Expense $expense): void
    {
        if ($expense->requester) {
            $expense->requester->notify(new ExpenseApprovedNotification($expense));
        }
    }

    public function notifyRejected(Expense $expense): void
    {
        if ($expense->requester) {
            $expense->requester->notify(new ExpenseRejectedNotification($expense));
        }
    }

    public function notifyFinanceForApprovedExpense(Expense $expense): void
    {
        $financeUsers = User::query()
            ->where('role', 'finance')
            ->get();

        foreach ($financeUsers as $user) {
            $user->notify(new ExpenseApprovedNotification($expense));
        }
    }
}