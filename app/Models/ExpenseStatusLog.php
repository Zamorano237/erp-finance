<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'user_id',
        'status_axis',
        'old_status',
        'new_status',
        'action',
        'comment',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
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