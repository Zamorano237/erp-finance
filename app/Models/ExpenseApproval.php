<?php

namespace App\Models;

use App\Enums\ExpenseApprovalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'approver_id',
        'status',
        'approval_order',
        'requested_at',
        'decided_at',
        'comment',
        'created_by',
    ];

    protected $casts = [
        'status' => ExpenseApprovalStatus::class,
        'requested_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}