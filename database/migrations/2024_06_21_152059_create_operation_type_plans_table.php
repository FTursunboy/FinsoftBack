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
        Schema::create('operation_type_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_plan_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('operation_type_id');
            $table->decimal('sum', 20, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_type_plans');
    }
};
