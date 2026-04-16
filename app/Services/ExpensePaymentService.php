<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseAllocation;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ExpensePaymentService
{
    public function __construct(
        private readonly ExpenseRecalculationService $recalculationService
    ) {
    }

    public function registerDirectPayment(
        Expense $expense,
        float $amount,
        string $paymentDate,
        ?string $paymentMethod = null,
        ?string $reference = null,
        ?string $comment = null,
        ?int $paidBy = null
    ): Payment {
        if ($expense->is_allocated && $expense->allocations()->exists()) {
            throw new InvalidArgumentException('Cette dépense est ventilée. Le paiement doit être enregistré sur une ligne ventilée.');
        }

        return DB::transaction(function () use (
            $expense,
            $amount,
            $paymentDate,
            $paymentMethod,
            $reference,
            $comment,
            $paidBy
        ) {
            $payment = Payment::create([
                'expense_id' => $expense->id,
                'expense_allocation_id' => null,
                'amount' => round($amount, 2),
                'payment_date' => $paymentDate,
                'payment_method' => $paymentMethod,
                'reference' => $reference,
                'comment' => $comment,
                'paid_by' => $paidBy,
            ]);

            $this->recalculationService->recalculateExpense($expense);

            return $payment->fresh();
        });
    }

    public function registerAllocationPayment(
        ExpenseAllocation $allocation,
        float $amount,
        string $paymentDate,
        ?string $paymentMethod = null,
        ?string $reference = null,
        ?string $comment = null,
        ?int $paidBy = null
    ): Payment {
        if ($allocation->status?->value === 'paid') {
            throw new InvalidArgumentException('Cette ligne ventilée est déjà soldée.');
        }

        return DB::transaction(function () use (
            $allocation,
            $amount,
            $paymentDate,
            $paymentMethod,
            $reference,
            $comment,
            $paidBy
        ) {
            $payment = Payment::create([
                'expense_id' => $allocation->expense_id,
                'expense_allocation_id' => $allocation->id,
                'amount' => round($amount, 2),
                'payment_date' => $paymentDate,
                'payment_method' => $paymentMethod,
                'reference' => $reference,
                'comment' => $comment,
                'paid_by' => $paidBy,
            ]);

            $this->recalculationService->recalculateAllocation($allocation);
            $this->recalculationService->recalculateExpense($allocation->expense);

            return $payment->fresh();
        });
    }

    public function deletePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $expense = $payment->expense;
            $allocation = $payment->allocation;

            $payment->delete();

            if ($allocation) {
                $this->recalculationService->recalculateAllocation($allocation);
            }

            $this->recalculationService->recalculateExpense($expense);
        });
    }
}