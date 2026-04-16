<?php

namespace App\Models;

use App\Enums\ExpenseContext;
use App\Enums\ExpenseType;
use App\Enums\ThirdPartyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseGenerationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'reference_prefix',
        'supplier_id',
        'third_party_name',
        'third_party_type',
        'expense_type',
        'expense_context',
        'label',
        'invoice_number_pattern',
        'amount_ht',
        'vat_amount',
        'amount_ttc',
        'payment_mode',
        'budget_category',
        'budget_version',
        'generation_start_date',
        'generation_end_date',
        'frequency',
        'generation_day',
        'auto_requires_approval',
        'auto_allocate',
        'allocation_mode',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'third_party_type' => ThirdPartyType::class,
        'expense_type' => ExpenseType::class,
        'expense_context' => ExpenseContext::class,
        'generation_start_date' => 'date',
        'generation_end_date' => 'date',
        'amount_ht' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'amount_ttc' => 'decimal:2',
        'auto_requires_approval' => 'boolean',
        'auto_allocate' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function generatedExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'generation_template_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(ExpenseGenerationBatch::class, 'template_id');
    }
}