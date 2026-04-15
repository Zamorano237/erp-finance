<?php

namespace App\Http\Controllers;

use App\Services\TreasurySimulationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreasuryController extends Controller
{
    public function index(Request $request, TreasurySimulationService $service): View
    {
        $balance = (float) $request->input('current_balance', 100000);
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        return view('treasury.index', [
            'simulation' => $service->simulate($balance, $from, $to),
            'filters' => [
                'current_balance' => $balance,
                'from_date' => $from,
                'to_date' => $to,
            ],
        ]);
    }
}
