<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'expense_allocation_id')) {
                $table->foreignId('expense_allocation_id')
                    ->nullable()
                    ->after('expense_id')
                    ->constrained('expense_allocations')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('payments', 'paid_by')) {
                $table->foreignId('paid_by')
                    ->nullable()
                    ->after('payment_date')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('payments', 'reference')) {
                $table->string('reference', 100)->nullable()->after('amount');
            }

            if (!Schema::hasColumn('payments', 'comment')) {
                $table->text('comment')->nullable()->after('reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'expense_allocation_id')) {
                $table->dropConstrainedForeignId('expense_allocation_id');
            }
            if (Schema::hasColumn('payments', 'paid_by')) {
                $table->dropConstrainedForeignId('paid_by');
            }
            foreach (['reference', 'comment'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};