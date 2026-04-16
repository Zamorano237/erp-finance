<?php

namespace App\Http\Controllers;

use App\Services\ExpenseCashForecastService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreasuryController extends Controller
{
    public function __construct(
        private readonly ExpenseCashForecastService $cashForecastService
    ) {
    }

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'current_balance' => ['nullable', 'numeric'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'scenario' => ['nullable', 'in:optimistic,realistic,pessimistic'],
            'alert_threshold' => ['nullable', 'numeric'],
            'include_forecast' => ['nullable', 'boolean'],
            'include_allocated_lines' => ['nullable', 'boolean'],
        ]);

        $simulation = $this->cashForecastService->simulate(
            currentBalance: (float) ($validated['current_balance'] ?? 0),
            fromDate: $validated['from_date'] ?? null,
            toDate: $validated['to_date'] ?? null,
            options: [
                'scenario' => $validated['scenario'] ?? 'realistic',
                'alert_threshold' => (float) ($validated['alert_threshold'] ?? 0),
                'include_forecast' => (bool) ($validated['include_forecast'] ?? true),
                'include_allocated_lines' => (bool) ($validated['include_allocated_lines'] ?? true),
            ],
        );

        return view('treasury.index', [
            'simulation' => $simulation,
        ]);
    }
}