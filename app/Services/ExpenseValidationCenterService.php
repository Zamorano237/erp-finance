<?php

namespace App\Services;

use App\Models\ExpenseApproval;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseValidationCenterService
{
    public function paginatePendingForUser(int $userId, int $perPage = 25): LengthAwarePaginator
    {
        return ExpenseApproval::query()
            ->with([
                'expense.supplier',
                'expense.requester',
            ])
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->orderByRaw('COALESCE(requested_at, created_at) asc')
            ->paginate($perPage);
    }

    public function countsForUser(int $userId): array
    {
        $base = ExpenseApproval::query()->where('approver_id', $userId);

        return [
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'approved' => (clone $base)->where('status', 'approved')->count(),
            'rejected' => (clone $base)->where('status', 'rejected')->count(),
            'cancelled' => (clone $base)->where('status', 'cancelled')->count(),
        ];
    }
}