<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('disk', 50)->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('size')->default(0);

            $table->boolean('is_primary')->default(false);
            $table->boolean('is_supporting_document')->default(true);

            $table->timestamps();

            $table->index(['expense_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_attachments');
    }
};