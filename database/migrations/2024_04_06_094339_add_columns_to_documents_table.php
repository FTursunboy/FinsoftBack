<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->text('comment')->nullable();
            $table->integer('saleInteger')->nullable();
            $table->integer('salePercent')->nullable();
            $table->foreignId('currency_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('comment');
            $table->dropColumn('saleInteger');
            $table->dropColumn('salePercent');
            $table->dropColumn('currency_id');
        });
    }
};
