<?php

namespace App\Services;

use App\Models\BudgetLine;
use App\Models\Expense;
use App\Models\Supplier;

class DashboardService
{
    public function summary(): array
    {
        $totalExpenses = (float) Expense::sum('amount_ttc');
        $totalPaid = (float) Expense::sum('amount_paid');
        $totalOutstanding = max(0, $totalExpenses - $totalPaid);
        $budget = (float) BudgetLine::sum('budget_amount');

        return [
            'supplier_count' => Supplier::count(),
            'expense_count' => Expense::count(),
            'total_expenses' => $totalExpenses,
            'total_paid' => $totalPaid,
            'total_outstanding' => $totalOutstanding,
            'total_budget' => $budget,
            'budget_gap' => $budget - $totalExpenses,
            'status_breakdown' => Expense::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
        ];
    }
}
