<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldValue extends Model
{
    protected $fillable = [
        'custom_field_id',
        'entity_type',
        'entity_id',
        'value_text',
        'value_number',
        'value_date',
        'value_boolean',
    ];

    protected $casts = [
        'value_number' => 'decimal:4',
        'value_date' => 'date',
        'value_boolean' => 'boolean',
    ];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function getResolvedValueAttribute()
    {
        if (! is_null($this->value_text)) {
            return $this->value_text;
        }

        if (! is_null($this->value_number)) {
            return $this->value_number;
        }

        if (! is_null($this->value_date)) {
            return optional($this->value_date)->format('Y-m-d');
        }

        if (! is_null($this->value_boolean)) {
            return $this->value_boolean;
        }

        return null;
    }
}