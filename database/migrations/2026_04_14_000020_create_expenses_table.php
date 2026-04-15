<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->index();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->nullable();
            $table->string('label');
            $table->date('invoice_date')->nullable();
            $table->date('receipt_date')->nullable();
            $table->date('service_start_date')->nullable();
            $table->date('service_end_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('planned_payment_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('amount_ht', 15, 2)->default(0);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('amount_ttc', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->string('status', 50)->default('ouverte');
            $table->string('validation_status', 50)->default('non_soumise');
            $table->string('payment_mode')->nullable();
            $table->string('third_party_type')->nullable();
            $table->string('budget_category')->nullable();
            $table->string('budget_version')->nullable();
            $table->boolean('is_forecast')->default(false);
            $table->boolean('is_allocated')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->text('comments')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
