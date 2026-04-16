<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];
    protected $casts = [
        'role' => UserRole::class,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }


    public function canWrite(): bool
    {
        return in_array($this->role, ['admin', 'finance'], true);
    }
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isFinance(): bool
    {
        return $this->role === UserRole::FINANCE;
    }

    public function isValidator(): bool
    {
        return $this->role === UserRole::VALIDATOR;
    }

    public function isReader(): bool
    {
        return $this->role === UserRole::READER;
    }

    public function canManageExpenses(): bool
{
    return in_array($this->role, [
        \App\Enums\UserRole::ADMIN,
        \App\Enums\UserRole::FINANCE,
    ], true);
}

    public function canValidateExpenses(): bool
    {
        return in_array($this->role, [
            UserRole::ADMIN,
            UserRole::VALIDATOR,
        ], true);
    }

    public function canReadExpenses(): bool
    {
        return true;
    }
}
