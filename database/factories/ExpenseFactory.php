<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        $amountTtc = fake()->randomFloat(2, 100, 5000);
        $amountPaid = 0;
        $balanceDue = $amountTtc;

        return [
            'reference' => 'EXP-' . fake()->unique()->numerify('######'),
            'supplier_id' => Supplier::factory(),
            'third_party_name' => fake()->company(),
            'invoice_number' => 'FAC-' . fake()->numerify('######'),
            'label' => fake()->sentence(4),

            'invoice_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'receipt_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'due_date' => fake()->dateTimeBetween('-1 month', '+2 months'),
            'planned_payment_date' => fake()->dateTimeBetween('-1 month', '+2 months'),
            'payment_date' => null,

            'amount_ht' => round($amountTtc / 1.2, 2),
            'vat_amount' => round($amountTtc - ($amountTtc / 1.2), 2),
            'amount_ttc' => $amountTtc,
            'amount_paid' => $amountPaid,
            'balance_due' => $balanceDue,

            'status' => 'open',
            'document_status' => 'received',
            'validation_status' => 'not_submitted',

            'payment_mode' => fake()->randomElement(['Virement', 'Prélèvement', 'CB']),
            'third_party_type' => 'supplier',
            'expense_type' => fake()->randomElement([
                'purchase',
                'bank',
                'social',
                'salary',
                'expense_report',
                'administration',
                'other',
            ]),
            'expense_context' => fake()->randomElement([
                'purchase_supplier',
                'bank_charge',
                'social_contribution',
                'salary_payment',
                'expense_reimbursement',
                'tax_or_admin',
                'exceptional',
            ]),

            'budget_category' => fake()->randomElement([
                'Prestations externes',
                'Informatique & digital',
                'Charges personnel',
                'Fournitures & fonctionnement',
                'Frais financiers & assurances',
            ]),
            'budget_version' => 'BUDGET-2026',
            'budget_origin' => 'manual',

            'is_forecast' => false,
            'is_allocated' => false,
            'allocation_mode' => null,
            'is_locked' => false,
            'cash_impact' => true,
            'is_regularizable' => false,
            'requires_approval' => false,

            'comments' => null,
            'requested_by' => User::factory(),
            'validated_by' => null,
            'validated_at' => null,
            'submitted_for_approval_at' => null,
            'submitted_for_approval_by' => null,
        ];
    }

    public function forecast(): static
    {
        return $this->state(fn () => [
            'is_forecast' => true,
            'document_status' => 'previsional',
            'status' => 'open',
        ]);
    }

    public function requiresApproval(): static
    {
        return $this->state(fn () => [
            'requires_approval' => true,
            'validation_status' => 'not_submitted',
        ]);
    }

    public function pendingApproval(int $validatorId, ?int $submitterId = null): static
    {
        return $this->state(fn () => [
            'requires_approval' => true,
            'validation_status' => 'pending',
            'status' => 'in_validation',
            'submitted_for_approval_at' => now()->subDays(2),
            'submitted_for_approval_by' => $submitterId,
            'validated_by' => null,
            'validated_at' => null,
        ]);
    }

    public function approved(int $validatorId): static
    {
        return $this->state(fn () => [
            'requires_approval' => true,
            'validation_status' => 'approved',
            'status' => 'waiting_payment',
            'validated_by' => $validatorId,
            'validated_at' => now()->subDay(),
        ]);
    }

    public function rejected(int $validatorId): static
    {
        return $this->state(fn () => [
            'requires_approval' => true,
            'validation_status' => 'rejected',
            'status' => 'rejected',
            'validated_by' => $validatorId,
            'validated_at' => now()->subDay(),
        ]);
    }

    public function partiallyPaid(): static
    {
        return $this->state(function (array $attributes) {
            $amountTtc = (float) $attributes['amount_ttc'];
            $paid = round($amountTtc * 0.4, 2);

            return [
                'amount_paid' => $paid,
                'balance_due' => round($amountTtc - $paid, 2),
                'status' => 'partially_paid',
                'payment_date' => now()->subDays(3),
            ];
        });
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $amountTtc = (float) $attributes['amount_ttc'];

            return [
                'amount_paid' => $amountTtc,
                'balance_due' => 0,
                'status' => 'paid',
                'payment_date' => now()->subDays(1),
            ];
        });
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'status' => 'overdue',
            'due_date' => now()->subDays(10),
            'payment_date' => null,
        ]);
    }

    public function allocated(): static
    {
        return $this->state(fn () => [
            'is_allocated' => true,
            'allocation_mode' => 'monthly_equal',
        ]);
    }
}