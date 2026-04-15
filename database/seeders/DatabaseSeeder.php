<?php

namespace Database\Seeders;

use App\Models\BudgetLine;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(OptionListSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@erp.local'],
            [
                'name' => 'Admin ERP',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ]
        );

        $supplier = Supplier::updateOrCreate(
            ['code' => 'FOU-001'],
            [
                'name' => 'Prestataire Démo',
                'category' => 'Prestataire',
                'auxiliary_account' => '401000',
                'frequency' => 'Mensuelle',
                'receipt_mode' => 'Email',
                'payment_mode' => 'Virement',
                'forecast_amount' => 1200,
                'default_label' => 'Prestation mensuelle',
                'payment_delay_days' => 30,
                'is_active' => true,
                'vat_rate_default' => 20,
                'third_party_type' => 'FOURNISSEUR',
                'budget_category' => 'Prestations externes',
                'email' => 'contact@prestataire-demo.local',
                'phone' => '0102030405',
                'notes' => 'Fournisseur de démonstration',
            ]
        );

        Expense::updateOrCreate(
            ['reference' => 'DEP-0001'],
            [
                'supplier_id' => $supplier->id,
                'invoice_number' => 'FAC-DEMO-001',
                'label' => 'Prestation Avril',
                'invoice_date' => now()->toDateString(),
                'receipt_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'planned_payment_date' => now()->addDays(30)->toDateString(),
                'payment_mode' => 'Virement',
                'third_party_type' => 'FOURNISSEUR',
                'budget_category' => 'Prestations externes',
                'budget_version' => 'V1',
                'amount_ht' => 1000,
                'vat_amount' => 200,
                'amount_ttc' => 1200,
                'amount_paid' => 200,
                'balance_due' => 1000,
                'status' => 'en_attente_paiement',
                'validation_status' => 'non_soumise',
                'is_forecast' => false,
                'is_allocated' => false,
                'is_locked' => false,
                'comments' => 'Dépense de démonstration',
            ]
        );

        BudgetLine::updateOrCreate(
            [
                'year' => now()->year,
                'month' => now()->month,
                'budget_category' => 'Prestations externes',
                'third_party_type' => 'FOURNISSEUR',
            ],
            [
                'supplier_name' => $supplier->name,
                'budget_amount' => 5000,
                'comments' => 'Budget de démonstration',
                'is_active' => true,
                'budget_is_active' => true,
                'budget_version' => 'V1',
                'generated_forecast' => false,
                'generation_mode' => null,
            ]
        );
    }
}