<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();

            $table->string('status', 50)->default('pending');
            $table->unsignedInteger('approval_order')->default(1);

            $table->timestamp('requested_at')->nullable();
            $table->timestamp('decided_at')->nullable();

            $table->text('comment')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['expense_id', 'status']);
            $table->index(['approver_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_approvals');
    }
};