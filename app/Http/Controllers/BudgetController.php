<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetLineRequest;
use App\Models\BudgetLine;
use App\Support\Enums;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(Request $request): View
    {
        $query = BudgetLine::query()->latest('year')->latest('month');

        if ($year = $request->integer('year')) {
            $query->where('year', $year);
        }

        return view('budgets.index', [
            'budgetLines' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('budgets.form', [
            'budgetLine' => new BudgetLine(['year' => now()->year, 'month' => now()->month]),
            'tiersTypes' => Enums::TIERS_TYPES,
            'budgetCategories' => Enums::BUDGET_CATEGORIES,
        ]);
    }

    public function store(BudgetLineRequest $request): RedirectResponse
    {
        BudgetLine::create($request->validated() + [
            'is_active' => true,
            'budget_is_active' => true,
            'generated_forecast' => false,
        ]);

        return redirect()->route('budgets.index')->with('success', 'Ligne budgétaire créée avec succès.');
    }

    public function edit(BudgetLine $budget): View
    {
        return view('budgets.form', [
            'budgetLine' => $budget,
            'tiersTypes' => Enums::TIERS_TYPES,
            'budgetCategories' => Enums::BUDGET_CATEGORIES,
        ]);
    }

    public function update(BudgetLineRequest $request, BudgetLine $budget): RedirectResponse
    {
        $budget->update($request->validated());

        return redirect()->route('budgets.index')->with('success', 'Ligne budgétaire mise à jour.');
    }

    public function destroy(BudgetLine $budget): RedirectResponse
    {
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Ligne budgétaire supprimée.');
    }
}
