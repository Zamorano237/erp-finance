<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();

            $table->string('module_code', 50);
            $table->string('field_code', 100);
            $table->string('label', 150);
            $table->string('field_type', 30); // text, number, date, boolean, select

            $table->foreignId('option_list_id')
                ->nullable()
                ->constrained('option_lists')
                ->nullOnDelete();

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_form')->default(true);
            $table->boolean('show_in_table')->default(false);
            $table->boolean('show_in_filters')->default(false);

            $table->integer('sort_order')->default(0);
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->string('default_value')->nullable();

            $table->timestamps();

            $table->unique(['module_code', 'field_code']);
            $table->index(['module_code', 'is_active']);
            $table->index(['module_code', 'field_type']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};