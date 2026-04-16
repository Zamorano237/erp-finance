<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseComment;

class ExpenseCommentService
{
    public function add(
        Expense $expense,
        int $userId,
        string $content,
        string $type = 'general',
        bool $isInternal = false
    ): ExpenseComment {
        return ExpenseComment::create([
            'expense_id' => $expense->id,
            'user_id' => $userId,
            'comment_type' => $type,
            'content' => $content,
            'is_internal' => $isInternal,
        ]);
    }
}