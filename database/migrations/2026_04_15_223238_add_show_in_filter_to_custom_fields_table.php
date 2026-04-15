<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->boolean('show_in_filter')->default(false)->after('show_in_table');
        });
    }

    public function down()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropColumn('show_in_filter');
        });
    }
};