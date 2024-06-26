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
        Schema::create('expense_item_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_plan_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('expense_items_id');
            $table->decimal('sum', 20, 3);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_item_plans');
    }
};
