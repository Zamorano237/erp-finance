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
            $table->foreignId('expense_allocation_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('amount', 15, 2);

            $table->date('payment_date');

            $table->string('payment_method')->nullable(); // ✅ AJOUT ICI
            $table->string('reference')->nullable();      // ⚠️ UNE SEULE FOIS

            $table->text('comment')->nullable();

            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
