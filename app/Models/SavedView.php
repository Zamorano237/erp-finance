<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module',
        'name',
        'description',
        'filters',
        'columns',
        'sort',
        'options',
        'is_default',
        'is_shared',
    ];

    protected $casts = [
        'filters' => 'array',
        'columns' => 'array',
        'sort' => 'array',
        'options' => 'array',
        'is_default' => 'boolean',
        'is_shared' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}