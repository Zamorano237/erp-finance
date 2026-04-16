<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_generation_batches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('template_id')->nullable()->constrained('expense_generation_templates')->nullOnDelete();
            $table->date('from_date');
            $table->date('to_date');

            $table->unsignedInteger('generated_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);

            $table->string('status', 50)->default('draft');
            $table->json('meta')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_generation_batches');
    }
};