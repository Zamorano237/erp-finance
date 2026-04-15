<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_list_id',
        'value',
        'label',
        'sort_order',
        'is_active',
        'is_default',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'metadata' => 'array',
    ];

    public function optionList(): BelongsTo
    {
        return $this->belongsTo(OptionList::class);
    }

    public function scopeForList(Builder $query, string $code): Builder
    {
        return $query->whereHas('optionList', function (Builder $q) use ($code) {
            $q->where('code', $code);
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }
}