<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseAllocation;
use App\Models\ExpenseApproval;
use App\Models\ExpenseComment;
use App\Models\ExpenseStatusLog;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@erp.test')->first();
        $finance = User::where('email', 'finance@erp.test')->first();
        $validator = User::where('email', 'validator@erp.test')->first();
        $reader = User::where('email', 'reader@erp.test')->first();

        $suppliers = Supplier::factory()->count(20)->create();

        $simpleOpen = Expense::factory()->create([
            'supplier_id' => $suppliers->random()->id,
            'requested_by' => $finance->id,
            'label' => 'Abonnement logiciel annuel',
            'status' => 'waiting_payment',
        ]);

        $partial = Expense::factory()
            ->partiallyPaid()
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Prestations consulting Q2',
            ]);

        Payment::factory()->create([
            'expense_id' => $partial->id,
            'paid_by' => $finance->id,
            'amount' => $partial->amount_paid,
        ]);

        $paid = Expense::factory()
            ->paid()
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Licence ERP mensuelle',
            ]);

        Payment::factory()->create([
            'expense_id' => $paid->id,
            'paid_by' => $finance->id,
            'amount' => $paid->amount_ttc,
        ]);

        $pending = Expense::factory()
            ->pendingApproval($validator->id, $finance->id)
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Mission de conseil stratégique',
            ]);

        ExpenseApproval::factory()->create([
            'expense_id' => $pending->id,
            'approver_id' => $validator->id,
            'created_by' => $finance->id,
            'status' => 'pending',
            'requested_at' => now()->subDays(2),
        ]);

        $approved = Expense::factory()
            ->approved($validator->id)
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Renouvellement maintenance annuelle',
            ]);

        ExpenseApproval::factory()->approved()->create([
            'expense_id' => $approved->id,
            'approver_id' => $validator->id,
            'created_by' => $finance->id,
        ]);

        $rejected = Expense::factory()
            ->rejected($validator->id)
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Dépense sans justificatif conforme',
            ]);

        ExpenseApproval::factory()->rejected()->create([
            'expense_id' => $rejected->id,
            'approver_id' => $validator->id,
            'created_by' => $finance->id,
        ]);

        $forecast = Expense::factory()
            ->forecast()
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Prévision URSSAF mois prochain',
            ]);

        $allocated = Expense::factory()
            ->allocated()
            ->create([
                'supplier_id' => $suppliers->random()->id,
                'requested_by' => $finance->id,
                'label' => 'Contrat annuel ventilé sur 12 mois',
                'amount_ttc' => 12000,
                'amount_paid' => 4000,
                'balance_due' => 8000,
                'status' => 'partially_paid',
            ]);

        ExpenseAllocation::factory()->create([
            'expense_id' => $allocated->id,
            'allocation_number' => 1,
            'label' => 'Janvier 2026',
            'amount' => 1000,
            'amount_paid' => 1000,
            'balance_due' => 0,
            'status' => 'paid',
            'is_locked' => true,
            'locked_at' => now()->subMonth(),
            'locked_by' => $finance->id,
        ]);

        ExpenseAllocation::factory()->create([
            'expense_id' => $allocated->id,
            'allocation_number' => 2,
            'label' => 'Février 2026',
            'amount' => 1000,
            'amount_paid' => 1000,
            'balance_due' => 0,
            'status' => 'paid',
            'is_locked' => true,
            'locked_at' => now()->subWeeks(3),
            'locked_by' => $finance->id,
        ]);

        ExpenseAllocation::factory()->partial()->create([
            'expense_id' => $allocated->id,
            'allocation_number' => 3,
            'label' => 'Mars 2026',
            'amount' => 1000,
        ]);

        ExpenseAllocation::factory()->count(9)->create([
            'expense_id' => $allocated->id,
            'status' => 'to_pay',
            'amount' => 1000,
            'amount_paid' => 0,
            'balance_due' => 1000,
        ]);

        ExpenseComment::create([
            'expense_id' => $pending->id,
            'user_id' => $finance->id,
            'comment_type' => 'general',
            'content' => 'Merci de vérifier la cohérence du bon de commande.',
            'is_internal' => false,
        ]);

        ExpenseComment::create([
            'expense_id' => $approved->id,
            'user_id' => $validator->id,
            'comment_type' => 'validation',
            'content' => 'Validation OK après revue des justificatifs.',
            'is_internal' => false,
        ]);

        ExpenseStatusLog::create([
            'expense_id' => $pending->id,
            'user_id' => $finance->id,
            'status_axis' => 'validation',
            'old_status' => 'not_submitted',
            'new_status' => 'pending',
            'action' => 'submit_for_approval',
            'comment' => null,
            'meta' => null,
        ]);

        ExpenseStatusLog::create([
            'expense_id' => $approved->id,
            'user_id' => $validator->id,
            'status_axis' => 'validation',
            'old_status' => 'pending',
            'new_status' => 'approved',
            'action' => 'approve',
            'comment' => 'Validation effectuée.',
            'meta' => null,
        ]);

        Expense::factory()->count(120)->create([
            'requested_by' => $finance->id,
        ]);
    }
}