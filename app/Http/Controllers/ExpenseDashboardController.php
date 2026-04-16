<?php

namespace App\Http\Controllers;

use App\Services\ExpenseDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseDashboardController extends Controller
{
    public function __construct(
        private readonly ExpenseDashboardService $dashboardService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'date_from',
            'date_to',
            'expense_type',
            'supplier_id',
        ]);

        return view('expenses.dashboard', [
            'summary' => $this->dashboardService->getOperationalSummary($filters),
            'breakdowns' => $this->dashboardService->getStatusBreakdowns($filters),
            'monthlyTrend' => $this->dashboardService->getMonthlyTrend($filters),
            'topSuppliers' => $this->dashboardService->getTopSuppliers($filters, 10),
            'filters' => $filters,
        ]);
    }
}