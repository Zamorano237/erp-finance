<?php

namespace App\Enums;

enum ExpenseAllocationStatus: string
{
    case PLANNED = 'planned';
    case TO_PAY = 'to_pay';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => 'Prévue',
            self::TO_PAY => 'À payer',
            self::PARTIALLY_PAID => 'Partiellement payée',
            self::PAID => 'Payée',
            self::CANCELLED => 'Annulée',
        };
    }
}