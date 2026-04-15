<?php

namespace App\Models;

use App\Models\CustomFieldValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'auxiliary_account',
        'frequency',
        'receipt_mode',
        'payment_mode',
        'forecast_amount',
        'default_label',
        'payment_delay_days',
        'is_active',
        'vat_rate_default',
        'third_party_type',
        'budget_category',
        'email',
        'phone',
        'notes',
    ];

    protected $casts = [
        'forecast_amount' => 'decimal:2',
        'payment_delay_days' => 'integer',
        'is_active' => 'boolean',
        'vat_rate_default' => 'decimal:2',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function customFieldValues(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class, 'entity_id')
            ->where('entity_type', 'suppliers');
    }

    public function getCustomFieldResolvedValue(int $customFieldId)
    {
        $value = $this->customFieldValues
            ->firstWhere('custom_field_id', $customFieldId);

        return $value?->resolved_value;
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('category', 'like', "%{$term}%")
                ->orWhere('third_party_type', 'like', "%{$term}%")
                ->orWhere('budget_category', 'like', "%{$term}%")
                ->orWhere('payment_mode', 'like', "%{$term}%")
                ->orWhere('receipt_mode', 'like', "%{$term}%");
        });
    }
}