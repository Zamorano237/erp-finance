<?php

namespace App\Support;

class Enums
{
    public const ROLES = ['admin', 'finance', 'reader'];

    public const TIERS_TYPES = [
        'FOURNISSEUR',
        'SALARIE',
        'ORGANISME_SOCIAL',
        'CLIENT',
        'BANQUE',
        'AUTRE',
    ];

    public const BUDGET_CATEGORIES = [
        'Charges personnel',
        'Prestations externes',
        'Informatique & digital',
        'Immobilier & exploitation',
        'Marketing & communication',
        'Déplacements & mobilité',
        'Fournitures & fonctionnement',
        'Frais financiers & assurances',
        'Obligations réglementaires',
        'Dépenses exceptionnelles',
    ];

    public const EXPENSE_STATUSES = [
        'ouverte',
        'en_attente_paiement',
        'partielle',
        'cloturee',
        'en_retard',
        'en_validation',
        'rejetee',
    ];

    public const VALIDATION_STATUSES = [
        'non_soumise',
        'en_attente_validation',
        'validee',
        'rejetee',
    ];
}
