<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    protected $fillable = [
        'module_code',
        'field_code',
        'label',
        'field_type',
        'option_list_id',
        'is_required',
        'is_active',
        'show_in_form',
        'show_in_table',
        'show_in_filters',
        'sort_order',
        'placeholder',
        'help_text',
        'default_value',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'show_in_form' => 'boolean',
        'show_in_table' => 'boolean',
        'show_in_filters' => 'boolean',
    ];

    public function optionList(): BelongsTo
    {
        return $this->belongsTo(OptionList::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function scopeForModule(Builder $query, string $moduleCode): Builder
    {
        return $query->where('module_code', $moduleCode);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeShowInForm(Builder $query): Builder
    {
        return $query->where('show_in_form', true);
    }

    public function scopeShowInTable(Builder $query): Builder
    {
        return $query->where('show_in_table', true);
    }

    public function optionValues(): array
    {
        if ($this->field_type !== 'select' || ! $this->optionList) {
            return [];
        }

        return $this->optionList->items()
            ->orderBy('sort_order')
            ->orderBy('label')
            ->pluck('label', 'value')
            ->toArray();
    }
}