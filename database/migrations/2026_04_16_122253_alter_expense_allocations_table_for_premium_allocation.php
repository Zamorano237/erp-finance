<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('expense_allocations')) {
            return;
        }

        if (!Schema::hasColumn('expense_allocations', 'is_locked')) {
            Schema::table('expense_allocations', function (Blueprint $table) {
                $table->boolean('is_locked')
                    ->default(false)
                    ->after('is_active');
            });
        }

        if (!Schema::hasColumn('expense_allocations', 'locked_at')) {
            Schema::table('expense_allocations', function (Blueprint $table) {
                $table->timestamp('locked_at')
                    ->nullable()
                    ->after('is_locked');
            });
        }

        if (!Schema::hasColumn('expense_allocations', 'locked_by')) {
            Schema::table('expense_allocations', function (Blueprint $table) {
                $table->foreignId('locked_by')
                    ->nullable()
                    ->after('locked_at')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('expense_allocations', 'meta')) {
            Schema::table('expense_allocations', function (Blueprint $table) {
                $table->json('meta')
                    ->nullable()
                    ->after('locked_by');
            });
        }

        Schema::table('expense_allocations', function (Blueprint $table) {
            $table->index('is_locked');
            $table->index('locked_by');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('expense_allocations')) {
            return;
        }

        if (Schema::hasColumn('expense_allocations', 'locked_by')) {
            Schema::table('expense_allocations', function (Blueprint $table) {
                $table->dropForeign(['locked_by']);
            });
        }

        Schema::table('expense_allocations', function (Blueprint $table) {
            $indexes = [
                'expense_allocations_is_locked_index',
                'expense_allocations_locked_by_index',
            ];

            foreach ($indexes as $index) {
                try {
                    $table->dropIndex($index);
                } catch (\Throwable $e) {
                    // Ignore si l’index n’existe pas
                }
            }
        });

        $columnsToDrop = [];

        foreach (['meta', 'locked_by', 'locked_at', 'is_locked'] as $column) {
            if (Schema::hasColumn('expense_allocations', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if (!empty($columnsToDrop)) {
            Schema::table('expense_allocations', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};