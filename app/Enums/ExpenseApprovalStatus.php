<?php

namespace App\Enums;

enum ExpenseApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::APPROVED => 'Validée',
            self::REJECTED => 'Rejetée',
            self::CANCELLED => 'Annulée',
        };
    }
}