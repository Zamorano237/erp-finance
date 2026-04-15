<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('auxiliary_account')->nullable();
            $table->string('frequency')->nullable();
            $table->string('receipt_mode')->nullable();
            $table->string('payment_mode')->nullable();
            $table->decimal('forecast_amount', 15, 2)->default(0);
            $table->string('default_label')->nullable();
            $table->integer('payment_delay_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->decimal('vat_rate_default', 5, 2)->default(20);
            $table->string('third_party_type')->nullable();
            $table->string('budget_category')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
