<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'third_party_type',
        'budget_category',
        'supplier_name',
        'budget_amount',
        'comments',
        'is_active',
        'budget_is_active',
        'budget_version',
        'generated_forecast',
        'forecast_generated_at',
        'generation_mode',
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'budget_is_active' => 'boolean',
        'generated_forecast' => 'boolean',
        'forecast_generated_at' => 'datetime',
    ];
}
