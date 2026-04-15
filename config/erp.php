<?php

return [
    'currency' => 'EUR',
    'default_budget_version' => 'V1',
    'statuses' => [
        'expenses' => [
            'ouverte',
            'en_attente_paiement',
            'partielle',
            'cloturee',
            'en_retard',
            'en_validation',
            'rejetee',
        ],
        'validation' => [
            'non_soumise',
            'en_attente_validation',
            'validee',
            'rejetee',
        ],
    ],
];
