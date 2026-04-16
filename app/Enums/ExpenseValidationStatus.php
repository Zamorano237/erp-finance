<?php

namespace App\Enums;

enum ExpenseValidationStatus: string
{
    case NOT_SUBMITTED = 'not_submitted';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::NOT_SUBMITTED => 'Non soumise',
            self::PENDING => 'En attente validation',
            self::APPROVED => 'Validée',
            self::REJECTED => 'Rejetée',
        };
    }
}