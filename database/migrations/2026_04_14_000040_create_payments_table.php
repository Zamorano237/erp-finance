<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expense_allocation_id')->nullable()->constrained('expense_allocations')->nullOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_mode')->nullable();
            $table->string('reference')->nullable();
            $table->string('status', 50)->default('valide');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
