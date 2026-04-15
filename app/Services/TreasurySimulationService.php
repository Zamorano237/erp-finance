<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;

class TreasurySimulationService
{
    public function simulate(float $currentBalance, ?string $fromDate = null, ?string $toDate = null): array
    {
        $start = $fromDate ? Carbon::parse($fromDate) : now()->startOfDay();
        $end = $toDate ? Carbon::parse($toDate) : now()->addDays(30)->endOfDay();

        $planned = Expense::query()
            ->whereBetween('planned_payment_date', [$start, $end])
            ->whereIn('status', ['ouverte', 'en_attente_paiement', 'partielle', 'en_retard'])
            ->get();

        $plannedOut = (float) $planned->sum('balance_due');
        $projectedBalance = $currentBalance - $plannedOut;

        return [
            'current_balance' => $currentBalance,
            'planned_out' => $plannedOut,
            'projected_balance' => $projectedBalance,
            'line_count' => $planned->count(),
            'lines' => $planned,
        ];
    }
}
