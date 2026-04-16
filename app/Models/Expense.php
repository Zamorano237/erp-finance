<?php

namespace App\Models;

use App\Enums\ExpenseContext;
use App\Enums\ExpenseDocumentStatus;
use App\Enums\ExpenseOperationalStatus;
use App\Enums\ExpenseType;
use App\Enums\ExpenseValidationStatus;
use App\Enums\ThirdPartyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'generation_template_id',
        'generation_batch_id',
        'realized_at',
        'reference',
        'supplier_id',
        'third_party_name',
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
        'document_status',
        'validation_status',

        'payment_mode',
        'third_party_type',
        'expense_type',
        'expense_context',

        'budget_category',
        'budget_version',
        'budget_origin',

        'is_forecast',
        'is_allocated',
        'allocation_mode',
        'is_locked',
        'cash_impact',
        'is_regularizable',
        'requires_approval',

        'comments',
        'requested_by',
        'validated_by',
        'validated_at',
        'submitted_for_approval_at',
        'submitted_for_approval_by',
    ];

    protected $casts = [
        'realized_at' => 'datetime',
        'invoice_date' => 'date',
        'receipt_date' => 'date',
        'service_start_date' => 'date',
        'service_end_date' => 'date',
        'due_date' => 'date',
        'planned_payment_date' => 'date',
        'payment_date' => 'date',
        'validated_at' => 'datetime',
        'submitted_for_approval_at' => 'datetime',

        'amount_ht' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'amount_ttc' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',

        'is_forecast' => 'boolean',
        'is_allocated' => 'boolean',
        'is_locked' => 'boolean',
        'requires_approval' => 'boolean',
        'cash_impact' => 'boolean',
        'is_regularizable' => 'boolean',

        'status' => ExpenseOperationalStatus::class,
        'document_status' => ExpenseDocumentStatus::class,
        'validation_status' => ExpenseValidationStatus::class,
        'expense_type' => ExpenseType::class,
        'expense_context' => ExpenseContext::class,
        'third_party_type' => ThirdPartyType::class,
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(ExpenseAllocation::class);
    }
    public function directPayments()
    {
        return $this->hasMany(Payment::class)->whereNull('expense_allocation_id');
    }

    public function getHasLockedAllocationsAttribute(): bool
    {
        return $this->allocations()->where('is_locked', true)->exists();
    }

    public function getCanRegenerateAllocationsAttribute(): bool
    {
        return !$this->has_locked_allocations;
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function approvals(): HasMany
    {
        return $this->hasMany(ExpenseApproval::class)->orderBy('approval_order');
    }

    public function commentsThread(): HasMany
    {
        return $this->hasMany(ExpenseComment::class)->latest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ExpenseAttachment::class)->latest();
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ExpenseStatusLog::class)->latest();
    }
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_for_approval_by');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->balance_due <= 0;
    }

    public function getIsPartiallyPaidAttribute(): bool
    {
        return $this->amount_paid > 0 && $this->balance_due > 0;
    }
    public function generationTemplate(): BelongsTo
    {
        return $this->belongsTo(ExpenseGenerationTemplate::class, 'generation_template_id');
    }

    public function generationBatch(): BelongsTo
    {
        return $this->belongsTo(ExpenseGenerationBatch::class, 'generation_batch_id');
    }
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        if (in_array($this->status, [
            ExpenseOperationalStatus::PAID,
            ExpenseOperationalStatus::CLOSED,
            ExpenseOperationalStatus::CANCELLED,
        ], true)) {
            return false;
        }

        return $this->due_date->isPast();
    }

    public function canBeSubmittedForApproval(): bool
    {
        return $this->requires_approval
            && $this->validation_status === ExpenseValidationStatus::NOT_SUBMITTED
            && $this->status !== ExpenseOperationalStatus::CANCELLED;
    }

    public function canBePaid(): bool
    {
        return in_array($this->status, [
            ExpenseOperationalStatus::OPEN,
            ExpenseOperationalStatus::WAITING_PAYMENT,
            ExpenseOperationalStatus::PARTIALLY_PAID,
        ], true);
    }

 public function syncComputedAmounts(): void
{
    $amountTtc = (float) ($this->amount_ttc ?? 0);
    $amountPaid = (float) ($this->amount_paid ?? 0);

    $this->balance_due = round($amountTtc - $amountPaid, 2);

    // Ne jamais écraser certains statuts métier explicites
    if (in_array($this->status, [
        ExpenseOperationalStatus::REJECTED,
        ExpenseOperationalStatus::CANCELLED,
        ExpenseOperationalStatus::CLOSED,
    ], true)) {
        return;
    }

    // Si la validation est rejetée, le statut opérationnel doit rester rejeté
    if ($this->validation_status === ExpenseValidationStatus::REJECTED) {
        $this->status = ExpenseOperationalStatus::REJECTED;
        return;
    }

    if ($this->balance_due <= 0 && $amountTtc > 0) {
        $this->status = ExpenseOperationalStatus::PAID;
    } elseif ($amountPaid > 0 && $this->balance_due > 0) {
        $this->status = ExpenseOperationalStatus::PARTIALLY_PAID;
    } elseif ($this->requires_approval && $this->validation_status === ExpenseValidationStatus::PENDING) {
        $this->status = ExpenseOperationalStatus::IN_VALIDATION;
    } elseif (
        $this->requires_approval &&
        $this->validation_status === ExpenseValidationStatus::APPROVED
    ) {
        $this->status = ExpenseOperationalStatus::WAITING_PAYMENT;
    } elseif ($amountTtc > 0) {
        $this->status = ExpenseOperationalStatus::WAITING_PAYMENT;
    } else {
        $this->status = ExpenseOperationalStatus::OPEN;
    }
}

    protected static function booted(): void
    {
        static::saving(function (Expense $expense) {
            $expense->syncComputedAmounts();
        });
    }
}
