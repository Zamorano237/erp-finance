<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('budget_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->string('third_party_type');
            $table->string('budget_category');
            $table->string('supplier_name')->nullable();
            $table->decimal('budget_amount', 15, 2)->default(0);
            $table->text('comments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('budget_is_active')->default(true);
            $table->string('budget_version')->default('V1');
            $table->boolean('generated_forecast')->default(false);
            $table->timestamp('forecast_generated_at')->nullable();
            $table->string('generation_mode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_lines');
    }
};
