<?php

namespace App\Services;

use App\Enums\ExpenseAllocationStatus;
use App\Enums\ExpenseOperationalStatus;
use App\Models\Expense;
use App\Models\ExpenseAllocation;

class ExpenseRecalculationService
{
    public function recalculateExpense(Expense $expense): Expense
    {
        $expense->loadMissing(['allocations.payments', 'payments']);

        if ($expense->is_allocated && $expense->allocations->count() > 0) {
            $this->recalculateAllocatedExpense($expense);
        } else {
            $this->recalculateDirectExpense($expense);
        }

        $expense->save();

        return $expense->fresh(['allocations', 'payments']);
    }

    public function recalculateAllocation(ExpenseAllocation $allocation): ExpenseAllocation
    {
        $paid = (float) $allocation->payments()->sum('amount');
        $amount = (float) $allocation->amount;
        $balance = round($amount - $paid, 2);

        $allocation->amount_paid = $paid;
        $allocation->balance_due = $balance;

        if ($balance <= 0 && $amount > 0) {
            $allocation->status = ExpenseAllocationStatus::PAID;
            $allocation->payment_date = $allocation->payments()->max('payment_date');
            $allocation->is_locked = true;
            $allocation->locked_at = now();
        } elseif ($paid > 0 && $balance > 0) {
            $allocation->status = ExpenseAllocationStatus::PARTIALLY_PAID;
        } else {
            $allocation->status = $allocation->planned_payment_date
                ? ExpenseAllocationStatus::TO_PAY
                : ExpenseAllocationStatus::PLANNED;
        }

        $allocation->save();

        return $allocation->fresh();
    }

    protected function recalculateAllocatedExpense(Expense $expense): void
    {
        $totalAmount = 0;
        $totalPaid = 0;
        $totalBalance = 0;

        foreach ($expense->allocations as $allocation) {
            $allocation = $this->recalculateAllocation($allocation);
            $totalAmount += (float) $allocation->amount;
            $totalPaid += (float) $allocation->amount_paid;
            $totalBalance += (float) $allocation->balance_due;
        }

        $expense->amount_ttc = round($totalAmount, 2);
        $expense->amount_paid = round($totalPaid, 2);
        $expense->balance_due = round($totalBalance, 2);

        if ($expense->balance_due <= 0 && $expense->amount_ttc > 0) {
            $expense->status = ExpenseOperationalStatus::PAID;
            $expense->payment_date = $expense->allocations()->max('payment_date');
        } elseif ($expense->amount_paid > 0 && $expense->balance_due > 0) {
            $expense->status = ExpenseOperationalStatus::PARTIALLY_PAID;
        } elseif ($expense->validation_status?->value === 'pending') {
            $expense->status = ExpenseOperationalStatus::IN_VALIDATION;
        } else {
            $expense->status = ExpenseOperationalStatus::WAITING_PAYMENT;
        }
    }

    protected function recalculateDirectExpense(Expense $expense): void
    {
        $paid = (float) $expense->directPayments()->sum('amount');
        $total = (float) $expense->amount_ttc;
        $balance = round($total - $paid, 2);

        $expense->amount_paid = $paid;
        $expense->balance_due = $balance;

        if ($balance <= 0 && $total > 0) {
            $expense->status = ExpenseOperationalStatus::PAID;
            $expense->payment_date = $expense->directPayments()->max('payment_date');
        } elseif ($paid > 0 && $balance > 0) {
            $expense->status = ExpenseOperationalStatus::PARTIALLY_PAID;
        } elseif ($expense->validation_status?->value === 'pending') {
            $expense->status = ExpenseOperationalStatus::IN_VALIDATION;
        } else {
            $expense->status = ExpenseOperationalStatus::WAITING_PAYMENT;
        }
    }
}