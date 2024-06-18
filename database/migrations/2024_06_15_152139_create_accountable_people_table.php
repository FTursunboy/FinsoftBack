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
        Schema::create('accountable_people', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->decimal('sum');
            $table->decimal('currency_sum');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('operation_type_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountable_people');
    }
};
