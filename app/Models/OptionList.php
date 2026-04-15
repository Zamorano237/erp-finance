<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionList extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'label',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OptionListItem::class)->orderBy('sort_order')->orderBy('label');
    }

    public static function valuesFor(string $code): array
    {
        $list = static::with('items')->where('code', $code)->first();

        return $list
            ? $list->items->where('is_active', true)->pluck('label')->values()->all()
            : [];
    }
}
