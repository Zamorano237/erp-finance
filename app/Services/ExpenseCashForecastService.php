<?php

namespace App\Services;

use App\Enums\ExpenseOperationalStatus;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseCashForecastService
{
    public function simulate(
        float $currentBalance,
        ?string $fromDate = null,
        ?string $toDate = null,
        array $options = []
    ): array {
        $start = $fromDate ? Carbon::parse($fromDate)->startOfDay() : now()->startOfDay();
        $end = $toDate ? Carbon::parse($toDate)->endOfDay() : now()->addDays(30)->endOfDay();

        $includeForecast = $options['include_forecast'] ?? true;
        $includeAllocatedLines = $options['include_allocated_lines'] ?? true;
        $scenario = $options['scenario'] ?? 'realistic';
        $alertThreshold = (float) ($options['alert_threshold'] ?? 0);

        $query = Expense::query()
            ->where('cash_impact', true)
            ->whereBetween('planned_payment_date', [$start, $end])
            ->whereIn('status', [
                ExpenseOperationalStatus::OPEN->value,
                ExpenseOperationalStatus::WAITING_PAYMENT->value,
                ExpenseOperationalStatus::PARTIALLY_PAID->value,
                ExpenseOperationalStatus::OVERDUE->value,
            ]);

        if (!$includeForecast) {
            $query->where('is_forecast', false);
        }

        $lines = $query
            ->with(['allocations'])
            ->orderBy('planned_payment_date')
            ->get();

        $simulationLines = [];

        foreach ($lines as $expense) {
            if ($expense->is_allocated && $includeAllocatedLines) {
                foreach ($expense->allocations as $allocation) {
                    if (!$allocation->planned_payment_date) {
                        continue;
                    }

                    $allocationDate = Carbon::parse($allocation->planned_payment_date);

                    if ($allocationDate->lt($start) || $allocationDate->gt($end)) {
                        continue;
                    }

                    $amount = (float) $allocation->balance_due;
                    $amount = $this->applyScenario($amount, $scenario);

                    $simulationLines[] = [
                        'type' => 'allocation',
                        'expense_id' => $expense->id,
                        'allocation_id' => $allocation->id,
                        'date' => $allocationDate->toDateString(),
                        'label' => $expense->label,
                        'third_party_name' => $expense->third_party_name ?: $expense->supplier?->name,
                        'amount' => $amount,
                        'status' => $expense->status->value,
                        'is_forecast' => (bool) $expense->is_forecast,
                    ];
                }

                continue;
            }

            $amount = (float) $expense->balance_due;
            $amount = $this->applyScenario($amount, $scenario);

            $simulationLines[] = [
                'type' => 'expense',
                'expense_id' => $expense->id,
                'allocation_id' => null,
                'date' => optional($expense->planned_payment_date)?->toDateString(),
                'label' => $expense->label,
                'third_party_name' => $expense->third_party_name ?: $expense->supplier?->name,
                'amount' => $amount,
                'status' => $expense->status->value,
                'is_forecast' => (bool) $expense->is_forecast,
            ];
        }

        usort($simulationLines, fn ($a, $b) => strcmp($a['date'], $b['date']));

        $projectedBalance = $currentBalance;
        $alerts = [];

        foreach ($simulationLines as &$line) {
            $projectedBalance -= $line['amount'];
            $line['projected_balance_after'] = round($projectedBalance, 2);

            if ($alertThreshold > 0 && $projectedBalance < $alertThreshold) {
                $alerts[] = [
                    'date' => $line['date'],
                    'expense_id' => $line['expense_id'],
                    'label' => $line['label'],
                    'projected_balance_after' => round($projectedBalance, 2),
                ];
            }
        }
        unset($line);

        return [
            'current_balance' => round($currentBalance, 2),
            'projected_balance' => round($projectedBalance, 2),
            'planned_out' => round(array_sum(array_column($simulationLines, 'amount')), 2),
            'line_count' => count($simulationLines),
            'scenario' => $scenario,
            'alert_threshold' => $alertThreshold,
            'alerts' => $alerts,
            'lines' => $simulationLines,
        ];
    }

    protected function applyScenario(float $amount, string $scenario): float
    {
        return match ($scenario) {
            'optimistic' => round($amount * 0.95, 2),
            'pessimistic' => round($amount * 1.05, 2),
            default => round($amount, 2),
        };
    }
}