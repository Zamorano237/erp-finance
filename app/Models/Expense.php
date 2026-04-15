<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'supplier_id',
        'invoice_number',
        'label',
        'invoice_date',
        'receipt_date',
        'service_start_date',
        'service_end_date',
        'due_date',
        'planned_payment_date',
        'payment_date',
        'amount_ht',
        'vat_amount',
        'amount_ttc',
        'amount_paid',
        'balance_due',
        'status',
        'validation_status',
        'payment_mode',
        'third_party_type',
        'budget_category',
        'budget_version',
        'is_forecast',
        'is_allocated',
        'is_locked',
        'comments',
        'requested_by',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'receipt_date' => 'date',
        'service_start_date' => 'date',
        'service_end_date' => 'date',
        'due_date' => 'date',
        'planned_payment_date' => 'date',
        'payment_date' => 'date',
        'validated_at' => 'datetime',
        'amount_ht' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'amount_ttc' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'is_forecast' => 'boolean',
        'is_allocated' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(ExpenseAllocation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
