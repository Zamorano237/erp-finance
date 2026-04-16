<?php

namespace App\Models;

use App\Enums\ExpenseAllocationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'allocation_number',
        'label',
        'start_date',
        'end_date',
        'amount',
        'amount_paid',
        'balance_due',
        'status',
        'planned_payment_date',
        'payment_date',
        'payment_mode',
        'is_locked',
        'locked_at',
        'locked_by',
        'meta',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'planned_payment_date' => 'date',
        'payment_date' => 'date',
        'locked_at' => 'datetime',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'is_locked' => 'boolean',
        'meta' => 'array',
        'status' => ExpenseAllocationStatus::class,
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'expense_allocation_id');
    }

    public function locker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->balance_due <= 0;
    }

    public function getIsPartiallyPaidAttribute(): bool
    {
        return $this->amount_paid > 0 && $this->balance_due > 0;
    }

    public function getCanBeEditedAttribute(): bool
    {
        return !$this->is_locked && $this->status !== ExpenseAllocationStatus::PAID;
    }
}