<?php

namespace App\Policies;

use App\Enums\ExpenseValidationStatus;
use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canReadExpenses();
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->canReadExpenses();
    }

    public function create(User $user): bool
    {
        return $user->canManageExpenses();
    }
    
    public function update(User $user, Expense $expense): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->canManageExpenses()) {
            return false;
        }

        if ($expense->status?->value === 'paid') {
            return false;
        }

        if ($expense->validation_status === ExpenseValidationStatus::APPROVED) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->isAdmin();
    }

    public function submitForApproval(User $user, Expense $expense): bool
    {
        if (!$user->canManageExpenses()) {
            return false;
        }

        return $expense->requires_approval
            && $expense->validation_status?->value === 'not_submitted';
    }

    public function approve(User $user, Expense $expense): bool
    {
        if (!$user->canValidateExpenses()) {
            return false;
        }

        return $expense->approvals()
            ->where('approver_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function reject(User $user, Expense $expense): bool
    {
        return $this->approve($user, $expense);
    }

    public function pay(User $user, Expense $expense): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isFinance();
    }

    public function manageAllocation(User $user, Expense $expense): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isFinance() && $expense->status?->value !== 'paid';
    }

    public function uploadAttachment(User $user, Expense $expense): bool
    {
        return $user->canManageExpenses();
    }

    public function comment(User $user, Expense $expense): bool
    {
        return $user->canReadExpenses();
    }
}
