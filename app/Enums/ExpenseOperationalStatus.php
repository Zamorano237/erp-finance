<?php

namespace App\Enums;

enum ExpenseOperationalStatus: string
{
    case DRAFT = 'draft';
    case OPEN = 'open';
    case IN_VALIDATION = 'in_validation';
    case WAITING_PAYMENT = 'waiting_payment';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';
    case CLOSED = 'closed';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::OPEN => 'Ouverte',
            self::IN_VALIDATION => 'En validation',
            self::WAITING_PAYMENT => 'En attente paiement',
            self::PARTIALLY_PAID => 'Partielle',
            self::PAID => 'Payée',
            self::CLOSED => 'Clôturée',
            self::OVERDUE => 'En retard',
            self::CANCELLED => 'Annulée',
            self::REJECTED => 'Rejetée',
        };
    }
}