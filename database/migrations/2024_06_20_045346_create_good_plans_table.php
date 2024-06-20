<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('good_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_sale_plan_id');
            $table->foreignId('month_id');
            $table->unsignedBigInteger('good_id');
            $table->string('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('good_plans');
    }
};
