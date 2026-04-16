<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('custom_field_id')
                ->constrained('custom_fields')
                ->cascadeOnDelete();

            $table->string('entity_type', 50); // suppliers, expenses, clients, etc.
            $table->unsignedBigInteger('entity_id');

            $table->text('value_text')->nullable();
            $table->decimal('value_number', 18, 4)->nullable();
            $table->date('value_date')->nullable();
            $table->boolean('value_boolean')->nullable();

            $table->timestamps();

            $table->unique(
                ['custom_field_id', 'entity_type', 'entity_id'],
                'uq_custom_field_entity'
            );

            $table->index(['entity_type', 'entity_id']);
            $table->index('custom_field_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
    }
};