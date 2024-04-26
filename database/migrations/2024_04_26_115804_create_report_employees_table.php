<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->integer('standart_hours');
            $table->integer('fact_hours');
            $table->unsignedBigInteger('report_card_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_employees');
    }
};
