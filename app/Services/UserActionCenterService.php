<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseApproval;
use App\Models\User;

class UserActionCenterService
{
    public function getForUser(User $user): array
    {
        $actions = [];

        if ($user->isValidator() || $user->isAdmin()) {
            $pendingApprovals = ExpenseApproval::query()
                ->with('expense')
                ->where('approver_id', $user->id)
                ->where('status', 'pending')
                ->orderByRaw('COALESCE(requested_at, created_at) asc')
                ->limit(10)
                ->get();

            $actions['pending_approvals'] = $pendingApprovals;
        }

        if ($user->isFinance() || $user->isAdmin()) {
            $overdueExpenses = Expense::query()
                ->where('status', 'overdue')
                ->latest()
                ->limit(10)
                ->get();

            $waitingPayments = Expense::query()
                ->whereIn('status', ['waiting_payment', 'partially_paid'])
                ->latest()
                ->limit(10)
                ->get();

            $actions['overdue_expenses'] = $overdueExpenses;
            $actions['waiting_payments'] = $waitingPayments;
        }

        return $actions;
    }

    public function getCounters(User $user): array
    {
        $counters = [
            'pending_approvals' => 0,
            'overdue_expenses' => 0,
            'waiting_payments' => 0,
            'unread_notifications' => $user->unreadNotifications()->count(),
        ];

        if ($user->isValidator() || $user->isAdmin()) {
            $counters['pending_approvals'] = ExpenseApproval::query()
                ->where('approver_id', $user->id)
                ->where('status', 'pending')
                ->count();
        }

        if ($user->isFinance() || $user->isAdmin()) {
            $counters['overdue_expenses'] = Expense::query()
                ->where('status', 'overdue')
                ->count();

            $counters['waiting_payments'] = Expense::query()
                ->whereIn('status', ['waiting_payment', 'partially_paid'])
                ->count();
        }

        return $counters;
    }
}