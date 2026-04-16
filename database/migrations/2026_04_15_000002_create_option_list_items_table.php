<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('option_list_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('option_list_id')
                ->constrained('option_lists')
                ->cascadeOnDelete();

            $table->string('value');
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique(['option_list_id', 'value']);
            $table->index(['option_list_id', 'is_active']);
            $table->index(['option_list_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_list_items');
    }
};