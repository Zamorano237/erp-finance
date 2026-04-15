<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'line_number',
        'period_label',
        'start_date',
        'end_date',
        'allocation_year',
        'allocation_month',
        'allocated_amount',
        'paid_amount',
        'balance_due',
        'percentage',
        'planned_payment_date',
        'payment_date',
        'payment_mode',
        'payment_reference',
        'status',
        'comments',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'planned_payment_date' => 'date',
        'payment_date' => 'date',
        'allocated_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
