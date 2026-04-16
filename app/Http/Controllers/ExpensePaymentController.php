<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseAllocation;
use App\Models\Payment;
use App\Services\ExpensePaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExpensePaymentController extends Controller
{
    public function __construct(
        private readonly ExpensePaymentService $paymentService
    ) {
    }

    public function payExpense(Request $request, Expense $expense): RedirectResponse
    {
        $this->authorize('pay', $expense);
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:100'],
            'comment' => ['nullable', 'string'],
        ]);

        $this->paymentService->registerDirectPayment(
            expense: $expense,
            amount: (float) $validated['amount'],
            paymentDate: $validated['payment_date'],
            paymentMethod: $validated['payment_method'] ?? null,
            reference: $validated['reference'] ?? null,
            comment: $validated['comment'] ?? null,
            paidBy: auth()->id(),
        );

        return back()->with('success', 'Paiement enregistré.');
    }

    public function payAllocation(Request $request, ExpenseAllocation $allocation): RedirectResponse
    {
        $this->authorize('pay', $allocation->expense);
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:100'],
            'comment' => ['nullable', 'string'],
        ]);

        $this->paymentService->registerAllocationPayment(
            allocation: $allocation,
            amount: (float) $validated['amount'],
            paymentDate: $validated['payment_date'],
            paymentMethod: $validated['payment_method'] ?? null,
            reference: $validated['reference'] ?? null,
            comment: $validated['comment'] ?? null,
            paidBy: auth()->id(),
        );

        return back()->with('success', 'Paiement de ligne ventilée enregistré.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $this->paymentService->deletePayment($payment);

        return back()->with('success', 'Paiement supprimé et montants recalculés.');
    }
}