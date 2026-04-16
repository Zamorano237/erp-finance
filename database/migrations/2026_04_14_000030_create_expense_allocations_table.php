<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expense_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('line_number')->default(1);
            $table->string('period_label')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unsignedSmallInteger('allocation_year')->nullable();
            $table->unsignedTinyInteger('allocation_month')->nullable();

            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);

            $table->decimal('percentage', 7, 4)->default(0);

            $table->date('planned_payment_date')->nullable();
            $table->date('payment_date')->nullable();

            $table->string('payment_mode', 100)->nullable();
            $table->string('payment_reference', 150)->nullable();

            $table->string('status', 50)->default('planned');
            $table->text('comments')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('expense_id');
            $table->index(['expense_id', 'line_number']);
            $table->index(['allocation_year', 'allocation_month']);
            $table->index('status');
            $table->index('planned_payment_date');
            $table->index('payment_date');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_allocations');
    }
};