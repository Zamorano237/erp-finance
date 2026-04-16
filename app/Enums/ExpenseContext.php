<?php

namespace App\Enums;

enum ExpenseContext: string
{
    case PURCHASE_SUPPLIER = 'purchase_supplier';
    case BANK_CHARGE = 'bank_charge';
    case SOCIAL_CONTRIBUTION = 'social_contribution';
    case SALARY_PAYMENT = 'salary_payment';
    case EXPENSE_REIMBURSEMENT = 'expense_reimbursement';
    case TAX_OR_ADMIN = 'tax_or_admin';
    case EXCEPTIONAL = 'exceptional';

    public function label(): string
    {
        return match ($this) {
            self::PURCHASE_SUPPLIER => 'Dépense fournisseur',
            self::BANK_CHARGE => 'Charge bancaire',
            self::SOCIAL_CONTRIBUTION => 'Charge sociale',
            self::SALARY_PAYMENT => 'Charge salariale',
            self::EXPENSE_REIMBURSEMENT => 'Note de frais',
            self::TAX_OR_ADMIN => 'Organisme / administration',
            self::EXCEPTIONAL => 'Dépense exceptionnelle',
        };
    }
}