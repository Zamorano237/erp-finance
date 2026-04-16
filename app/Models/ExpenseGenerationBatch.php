<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseGenerationBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'from_date',
        'to_date',
        'generated_count',
        'skipped_count',
        'status',
        'meta',
        'created_by',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'meta' => 'array',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ExpenseGenerationTemplate::class, 'template_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'generation_batch_id');
    }
}