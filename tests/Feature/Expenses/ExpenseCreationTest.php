<?php

namespace Tests\Feature\Expenses;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDemoUsers;
use Tests\TestCase;

class ExpenseCreationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDemoUsers;

    public function test_finance_can_create_an_expense(): void
    {
        $finance = $this->createFinance();
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($finance)->post(route('expenses.store'), [
            'reference' => 'EXP-000001',
            'supplier_id' => $supplier->id,
            'third_party_name' => $supplier->name,
            'label' => 'Test création dépense',
            'amount_ttc' => 1200,
            'amount_ht' => 1000,
            'vat_amount' => 200,
            'third_party_type' => 'supplier',
            'expense_type' => 'purchase',
            'payment_mode' => 'Virement',
            'is_forecast' => false,
            'requires_approval' => false,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'reference' => 'EXP-000001',
            'label' => 'Test création dépense',
            'amount_ttc' => 1200,
        ]);
    }

    public function test_reader_cannot_create_an_expense(): void
    {
        $reader = $this->createReader();
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($reader)->post(route('expenses.store'), [
            'reference' => 'EXP-000002',
            'supplier_id' => $supplier->id,
            'third_party_name' => $supplier->name,
            'label' => 'Test refus création',
            'amount_ttc' => 900,
            'third_party_type' => 'supplier',
            'expense_type' => 'purchase',
        ]);

        $response->assertForbidden();
    }
}