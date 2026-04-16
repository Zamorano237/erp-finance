<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_generation_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('reference_prefix', 50)->nullable();

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('third_party_name')->nullable();
            $table->string('third_party_type', 50);
            $table->string('expense_type', 50);
            $table->string('expense_context', 100);

            $table->string('label');
            $table->string('invoice_number_pattern', 100)->nullable();

            $table->decimal('amount_ht', 15, 2)->nullable();
            $table->decimal('vat_amount', 15, 2)->nullable();
            $table->decimal('amount_ttc', 15, 2);

            $table->string('payment_mode', 100)->nullable();
            $table->string('budget_category')->nullable();
            $table->string('budget_version', 100)->nullable();

            $table->date('generation_start_date');
            $table->date('generation_end_date')->nullable();

            $table->string('frequency', 50); // monthly, quarterly, yearly...
            $table->unsignedInteger('generation_day')->nullable();

            $table->boolean('auto_requires_approval')->default(false);
            $table->boolean('auto_allocate')->default(false);
            $table->string('allocation_mode', 50)->nullable();

            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_generation_templates');
    }
};