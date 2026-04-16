<?php

namespace App\Enums;

enum ThirdPartyType: string
{
    case SUPPLIER = 'supplier';
    case BANK = 'bank';
    case ORGANIZATION = 'organization';
    case EMPLOYEE = 'employee';
    case ADMINISTRATION = 'administration';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::SUPPLIER => 'Fournisseur',
            self::BANK => 'Banque',
            self::ORGANIZATION => 'Organisme',
            self::EMPLOYEE => 'Salarié',
            self::ADMINISTRATION => 'Administration',
            self::OTHER => 'Autre',
        };
    }
}