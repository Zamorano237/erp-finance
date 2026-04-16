<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case FINANCE = 'finance';
    case VALIDATOR = 'validator';
    case READER = 'reader';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::FINANCE => 'Finance',
            self::VALIDATOR => 'Validateur',
            self::READER => 'Lecture seule',
        };
    }
}