<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseGenerationTemplate;
use App\Services\ExpenseGenerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExpenseGenerationController extends Controller
{
    public function __construct(
        private readonly ExpenseGenerationService $generationService
    ) {
    }

    public function generate(Request $request, ExpenseGenerationTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
        ]);

        $batch = $this->generationService->generate(
            template: $template,
            fromDate: $validated['from_date'],
            toDate: $validated['to_date'],
            userId: auth()->id(),
        );

        return back()->with('success', "Génération terminée. {$batch->generated_count} dépense(s) créée(s).");
    }

    public function realize(Request $request, Expense $expense): RedirectResponse
    {
        $this->generationService->realize($expense, $request->all());

        return back()->with('success', 'Occurrence prévisionnelle transformée en dépense réelle.');
    }
}