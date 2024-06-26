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
        Schema::rename('good_sale_plans', 'sale_plans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('sale_plan', 'good_sale_plans');
    }
};
