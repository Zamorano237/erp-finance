<?php

namespace App\Http\Controllers;

use App\Services\ExpenseValidationCenterService;
use Illuminate\View\View;

class ExpenseValidationCenterController extends Controller
{
    public function __construct(
        private readonly ExpenseValidationCenterService $validationCenterService
    ) {
    }

    public function index(): View
    {
        $pendingApprovals = $this->validationCenterService->paginatePendingForUser(auth()->id(), 25);
        $counts = $this->validationCenterService->countsForUser(auth()->id());

        return view('expenses.validation-center', [
            'pendingApprovals' => $pendingApprovals,
            'counts' => $counts,
        ]);
    }
}