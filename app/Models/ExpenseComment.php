<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'user_id',
        'comment_type',
        'content',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}