<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('status_axis', 50); // document | operational | validation
            $table->string('old_status', 100)->nullable();
            $table->string('new_status', 100)->nullable();

            $table->string('action', 100)->nullable();
            $table->text('comment')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['expense_id', 'status_axis']);
            $table->index(['status_axis', 'new_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_status_logs');
    }
};