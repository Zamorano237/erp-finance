<?php

namespace App\Enums;

enum ExpenseDocumentStatus: string
{
    case PREVISIONAL = 'previsional';
    case MISSING_DOCUMENT = 'missing_document';
    case NO_INVOICE = 'no_invoice';
    case TO_REGULARIZE = 'to_regularize';
    case RECEIVED = 'received';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::PREVISIONAL => 'Prévisionnelle',
            self::MISSING_DOCUMENT => 'En attente de justificatif',
            self::NO_INVOICE => 'Paiement sans facture',
            self::TO_REGULARIZE => 'À régulariser',
            self::RECEIVED => 'Reçue',
            self::ARCHIVED => 'Archivée',
        };
    }
}