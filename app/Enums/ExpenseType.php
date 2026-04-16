<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpenseType: string
{
    case PURCHASE = 'purchase';
    case BANK = 'bank';
    case SOCIAL = 'social';
    case SALARY = 'salary';
    case EXPENSE_REPORT = 'expense_report';
    case ADMINISTRATION = 'administration';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PURCHASE => 'Fournisseur',
            self::BANK => 'Banque',
            self::SOCIAL => 'Organisme social',
            self::SALARY => 'Salaire / salarié',
            self::EXPENSE_REPORT => 'Note de frais',
            self::ADMINISTRATION => 'Administration',
            self::OTHER => 'Autre',
        };
    }
}