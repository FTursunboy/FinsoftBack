<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('good_sale_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->integer('year');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('good_sale_plans');
    }
};
