<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'uploaded_by',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'is_primary',
        'is_supporting_document',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_supporting_document' => 'boolean',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}