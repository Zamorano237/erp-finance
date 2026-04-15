<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTablePreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_key',
        'columns',
        'filters',
        'sort_by',
        'sort_direction',
    ];

    protected $casts = [
        'columns' => 'array',
        'filters' => 'array',
    ];
}
