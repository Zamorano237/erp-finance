<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('document_status', 50)
                ->default('received')
                ->after('validation_status');

            $table->string('expense_type', 50)
                ->nullable()
                ->after('third_party_type');

            $table->string('expense_context', 100)
                ->nullable()
                ->after('expense_type');

            $table->string('third_party_name')
                ->nullable()
                ->after('supplier_id');

            $table->boolean('requires_approval')
                ->default(false)
                ->after('is_locked');

            $table->timestamp('submitted_for_approval_at')
                ->nullable()
                ->after('validated_at');

            $table->unsignedBigInteger('submitted_for_approval_by')
                ->nullable()
                ->after('submitted_for_approval_at');

            $table->string('allocation_mode', 50)
                ->nullable()
                ->after('is_allocated');

            $table->string('budget_origin', 50)
                ->nullable()
                ->after('budget_version');

            $table->boolean('cash_impact')
                ->default(true)
                ->after('budget_origin');

            $table->boolean('is_regularizable')
                ->default(false)
                ->after('cash_impact');
        });

        DB::table('expenses')
            ->whereNull('document_status')
            ->update(['document_status' => 'received']);

        DB::table('expenses')
            ->whereNull('expense_type')
            ->update(['expense_type' => 'purchase']);

        DB::table('expenses')
            ->whereNull('expense_context')
            ->update(['expense_context' => 'purchase_supplier']);

        DB::table('expenses')
            ->whereNull('third_party_name')
            ->update([
                'third_party_name' => DB::raw("COALESCE(label, reference)")
            ]);
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn([
                'document_status',
                'expense_type',
                'expense_context',
                'third_party_name',
                'requires_approval',
                'submitted_for_approval_at',
                'submitted_for_approval_by',
                'allocation_mode',
                'budget_origin',
                'cash_impact',
                'is_regularizable',
            ]);
        });
    }
};