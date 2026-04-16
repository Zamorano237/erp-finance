<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseStatusLog;

class ExpenseStatusLogService
{
    public function log(
        Expense $expense,
        string $axis,
        ?string $oldStatus,
        ?string $newStatus,
        ?int $userId = null,
        ?string $action = null,
        ?string $comment = null,
        ?array $meta = null
    ): ExpenseStatusLog {
        return ExpenseStatusLog::create([
            'expense_id' => $expense->id,
            'user_id' => $userId,
            'status_axis' => $axis,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'action' => $action,
            'comment' => $comment,
            'meta' => $meta,
        ]);
    }
}