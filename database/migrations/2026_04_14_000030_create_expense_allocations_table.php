<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
            $table->integer('line_number')->default(1);
            $table->string('period_label')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('allocation_year')->nullable();
            $table->integer('allocation_month')->nullable();
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->date('planned_payment_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('status', 50)->default('a_payer');
            $table->text('comments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_allocations');
    }
};
