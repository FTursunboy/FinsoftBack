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
        Schema::create('employee_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_plan_id');
            $table->foreignId('month_id');
            $table->unsignedBigInteger('employee_id');
            $table->decimal('sum',20,3);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_plans');
    }
};
