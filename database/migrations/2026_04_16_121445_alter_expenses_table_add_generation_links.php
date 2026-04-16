<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('generation_template_id')
                ->nullable()
                ->after('submitted_for_approval_by')
                ->constrained('expense_generation_templates')
                ->nullOnDelete();

            $table->foreignId('generation_batch_id')
                ->nullable()
                ->after('generation_template_id')
                ->constrained('expense_generation_batches')
                ->nullOnDelete();

            $table->timestamp('realized_at')
                ->nullable()
                ->after('generation_batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('generation_template_id');
            $table->dropConstrainedForeignId('generation_batch_id');
            $table->dropColumn('realized_at');
        });
    }
};