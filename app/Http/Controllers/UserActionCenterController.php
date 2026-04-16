<?php

namespace App\Http\Controllers;

use App\Services\UserActionCenterService;
use Illuminate\View\View;

class UserActionCenterController extends Controller
{
    public function __construct(
        private readonly UserActionCenterService $actionCenterService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();

        return view('action-center.index', [
            'actions' => $this->actionCenterService->getForUser($user),
            'counters' => $this->actionCenterService->getCounters($user),
        ]);
    }
}