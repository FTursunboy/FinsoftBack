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
        Schema::create('old_new_client_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_plan_id');
            $table->tinyInteger('month_id');
            $table->integer('new_client');
            $table->integer('old_client');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_new_client_plans');
    }
};
