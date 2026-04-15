<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'expense_allocation_id',
        'payment_date',
        'amount',
        'payment_mode',
        'reference',
        'status',
        'comments',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(ExpenseAllocation::class, 'expense_allocation_id');
    }
}
