<?php

namespace Database\Seeders;

use App\Models\OptionList;
use Illuminate\Database\Seeder;

class OptionListSeeder extends Seeder
{
    public function run(): void
    {
        $lists = [
            'supplier_categories' => [
                'label' => 'Catégories fournisseurs',
                'items' => ['Prestataire', 'Frais bancaires', 'Loyer', 'Assurance', 'Organisme social', 'Banque', 'Autre'],
            ],
            'supplier_frequencies' => [
                'label' => 'Fréquences',
                'items' => ['Ponctuelle', 'Mensuelle', 'Trimestrielle', 'Semestrielle', 'Annuelle'],
            ],
            'supplier_receipt_modes' => [
                'label' => 'Modes de réception',
                'items' => ['Email', 'Courrier', 'Portail', 'Relevé / prélèvement', 'Manuel'],
            ],
            'supplier_payment_modes' => [
                'label' => 'Modes de règlement',
                'items' => ['Virement', 'Prélèvement', 'Carte', 'Chèque', 'Espèces'],
            ],
            'tier_types' => [
                'label' => 'Types tiers',
                'items' => ['FOURNISSEUR', 'BANQUE', 'ORGANISME_SOCIAL', 'SALARIE', 'CLIENT', 'AUTRE'],
            ],
            'budget_categories' => [
                'label' => 'Catégories budgétaires',
                'items' => ['Prestations externes', 'Frais financiers & assurances', 'Charges personnel', 'Déplacements & mobilité', 'Informatique & digital', 'Fournitures & fonctionnement'],
            ],
        ];

        foreach ($lists as $code => $config) {
            $list = OptionList::updateOrCreate(
                ['code' => $code],
                ['label' => $config['label'], 'description' => $config['label']]
            );

            foreach ($config['items'] as $index => $item) {
                $list->items()->updateOrCreate(
                    ['value' => \Illuminate\Support\Str::slug($item, '_')],
                    [
                        'label' => $item,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                        'is_default' => $index === 0,
                    ]
                );
            }
        }
    }
}
