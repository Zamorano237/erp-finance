<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseAllocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ExpenseAllocationService
{
    public function __construct(
        private readonly ExpenseRecalculationService $recalculationService
    ) {
    }

    public function replaceWithMonthlyEqualAllocation(
        Expense $expense,
        string $startDate,
        string $endDate,
        ?string $paymentMode = null
    ): Expense {
        return DB::transaction(function () use ($expense, $startDate, $endDate, $paymentMode) {
            $this->guardCanRebuildAllocation($expense);

            $expense->allocations()->delete();

            $start = Carbon::parse($startDate)->startOfMonth();
            $end = Carbon::parse($endDate)->startOfMonth();

            if ($end->lt($start)) {
                throw new InvalidArgumentException('La date de fin doit être supérieure ou égale à la date de début.');
            }

            $months = [];
            $cursor = $start->copy();

            while ($cursor->lte($end)) {
                $months[] = $cursor->copy();
                $cursor->addMonth();
            }

            $count = count($months);
            if ($count === 0) {
                throw new InvalidArgumentException('Aucune période de ventilation calculée.');
            }

            $total = round((float) $expense->amount_ttc, 2);
            $base = floor(($total / $count) * 100) / 100;
            $allocated = 0;

            foreach ($months as $index => $month) {
                $amount = $index === ($count - 1)
                    ? round($total - $allocated, 2)
                    : $base;

                $allocated += $amount;

                ExpenseAllocation::create([
                    'expense_id' => $expense->id,
                    'allocation_number' => $index + 1,
                    'label' => $expense->label . ' - ' . $month->translatedFormat('F Y'),
                    'start_date' => $month->copy()->startOfMonth(),
                    'end_date' => $month->copy()->endOfMonth(),
                    'amount' => $amount,
                    'amount_paid' => 0,
                    'balance_due' => $amount,
                    'planned_payment_date' => $month->copy()->endOfMonth(),
                    'payment_mode' => $paymentMode ?: $expense->payment_mode,
                    'status' => 'to_pay',
                    'is_locked' => false,
                ]);
            }

            $expense->is_allocated = true;
            $expense->allocation_mode = 'monthly_equal';
            $expense->save();

            return $this->recalculationService->recalculateExpense($expense);
        });
    }

    public function replaceWithManualAllocation(Expense $expense, array $lines): Expense
    {
        return DB::transaction(function () use ($expense, $lines) {
            $this->guardCanRebuildAllocation($expense);

            $expense->allocations()->delete();

            $total = 0;

            foreach ($lines as $index => $line) {
                $amount = round((float) ($line['amount'] ?? 0), 2);
                $total += $amount;

                ExpenseAllocation::create([
                    'expense_id' => $expense->id,
                    'allocation_number' => $index + 1,
                    'label' => $line['label'] ?? ($expense->label . ' - Ligne ' . ($index + 1)),
                    'start_date' => $line['start_date'] ?? null,
                    'end_date' => $line['end_date'] ?? null,
                    'amount' => $amount,
                    'amount_paid' => 0,
                    'balance_due' => $amount,
                    'planned_payment_date' => $line['planned_payment_date'] ?? null,
                    'payment_mode' => $line['payment_mode'] ?? $expense->payment_mode,
                    'status' => !empty($line['planned_payment_date']) ? 'to_pay' : 'planned',
                    'is_locked' => false,
                    'meta' => $line['meta'] ?? null,
                ]);
            }

            $expenseAmount = round((float) $expense->amount_ttc, 2);
            if (round($total, 2) !== $expenseAmount) {
                throw new InvalidArgumentException("Le total ventilé ({$total}) doit être égal au montant de la dépense ({$expenseAmount}).");
            }

            $expense->is_allocated = true;
            $expense->allocation_mode = 'manual';
            $expense->save();

            return $this->recalculationService->recalculateExpense($expense);
        });
    }

    public function removeAllocation(Expense $expense): Expense
    {
        return DB::transaction(function () use ($expense) {
            $this->guardCanRebuildAllocation($expense);

            $expense->allocations()->delete();
            $expense->is_allocated = false;
            $expense->allocation_mode = null;
            $expense->save();

            return $this->recalculationService->recalculateExpense($expense);
        });
    }

    protected function guardCanRebuildAllocation(Expense $expense): void
    {
        if ($expense->allocations()->where('is_locked', true)->exists()) {
            throw new InvalidArgumentException('Impossible de reconstruire la ventilation : au moins une ligne est déjà verrouillée car payée.');
        }
    }
}