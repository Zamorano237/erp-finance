<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\ExpenseAllocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExpenseAllocationController extends Controller
{
    public function __construct(
        private readonly ExpenseAllocationService $allocationService
    ) {}

    public function monthlyEqual(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('manageAllocation', $expense);
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'payment_mode' => ['nullable', 'string', 'max:100'],
        ]);

        $this->allocationService->replaceWithMonthlyEqualAllocation(
            expense: $expense,
            startDate: $validated['start_date'],
            endDate: $validated['end_date'],
            paymentMode: $validated['payment_mode'] ?? null,
        );

        return back()->with('success', 'Ventilation mensuelle générée avec succès.');
    }

    public function manual(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('manageAllocation', $expense);
        $validated = $request->validate([
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.label' => ['nullable', 'string', 'max:255'],
            'lines.*.start_date' => ['nullable', 'date'],
            'lines.*.end_date' => ['nullable', 'date'],
            'lines.*.planned_payment_date' => ['nullable', 'date'],
            'lines.*.payment_mode' => ['nullable', 'string', 'max:100'],
            'lines.*.amount' => ['required', 'numeric', 'min:0'],
        ]);

        $this->allocationService->replaceWithManualAllocation(
            expense: $expense,
            lines: $validated['lines'],
        );

        return back()->with('success', 'Ventilation manuelle enregistrée avec succès.');
    }

    public function remove(Expense $expense): RedirectResponse
    {
        $this->authorize('manageAllocation', $expense);
        $this->allocationService->removeAllocation($expense);

        return back()->with('success', 'Ventilation supprimée.');
    }
}
