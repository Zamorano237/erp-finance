<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseAllocation;
use App\Models\Supplier;
use App\Support\Enums;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Expense::query()->with('supplier')->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($inner) use ($search) {
                $inner->where('label', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        return view('expenses.index', [
            'expenses' => $query->paginate(15)->withQueryString(),
            'statuses' => Enums::EXPENSE_STATUSES,
        ]);
    }

    public function create(): View
    {
        return view('expenses.form', [
            'expense' => new Expense(['status' => 'ouverte', 'validation_status' => 'non_soumise']),
            'suppliers' => Supplier::orderBy('name')->get(),
            'statuses' => Enums::EXPENSE_STATUSES,
            'validationStatuses' => Enums::VALIDATION_STATUSES,
            'budgetCategories' => Enums::BUDGET_CATEGORIES,
            'tiersTypes' => Enums::TIERS_TYPES,
        ]);
    }

    public function store(ExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['reference'] = $data['reference'] ?: 'DEP-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        $data['amount_paid'] = $data['amount_paid'] ?? 0;
        $data['balance_due'] = max(0, (float) $data['amount_ttc'] - (float) $data['amount_paid']);

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Dépense créée avec succès.');
    }

    public function edit(Expense $expense): View
    {
        $expense->load(['allocations', 'payments']);

        return view('expenses.form', [
            'expense' => $expense,
            'suppliers' => Supplier::orderBy('name')->get(),
            'statuses' => Enums::EXPENSE_STATUSES,
            'validationStatuses' => Enums::VALIDATION_STATUSES,
            'budgetCategories' => Enums::BUDGET_CATEGORIES,
            'tiersTypes' => Enums::TIERS_TYPES,
        ]);
    }

    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $data = $request->validated();
        $data['amount_paid'] = $data['amount_paid'] ?? 0;
        $data['balance_due'] = max(0, (float) $data['amount_ttc'] - (float) $data['amount_paid']);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour avec succès.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée.');
    }

    public function generateAllocation(Expense $expense): RedirectResponse
    {
        $expense->loadMissing('allocations');

        if ($expense->allocations()->exists()) {
            return redirect()->route('expenses.edit', $expense)->with('success', 'Ventilation déjà générée.');
        }

        $start = $expense->service_start_date ?? $expense->invoice_date ?? now();
        $end = $expense->service_end_date ?? $start;
        $months = max(1, $start->diffInMonths($end) + 1);
        $baseAmount = round((float) $expense->amount_ttc / $months, 2);

        for ($i = 0; $i < $months; $i++) {
            $period = $start->copy()->addMonths($i);
            $amount = $i === ($months - 1)
                ? round((float) $expense->amount_ttc - ($baseAmount * ($months - 1)), 2)
                : $baseAmount;

            ExpenseAllocation::create([
                'expense_id' => $expense->id,
                'line_number' => $i + 1,
                'period_label' => $period->translatedFormat('M Y'),
                'start_date' => $period->copy()->startOfMonth(),
                'end_date' => $period->copy()->endOfMonth(),
                'allocation_year' => (int) $period->format('Y'),
                'allocation_month' => (int) $period->format('m'),
                'allocated_amount' => $amount,
                'paid_amount' => 0,
                'balance_due' => $amount,
                'percentage' => round(100 / $months, 2),
                'planned_payment_date' => $period->copy()->endOfMonth(),
                'payment_mode' => $expense->payment_mode,
                'status' => 'a_payer',
                'is_active' => true,
            ]);
        }

        $expense->update(['is_allocated' => true]);

        return redirect()->route('expenses.edit', $expense)->with('success', 'Ventilation générée avec succès.');
    }
}
