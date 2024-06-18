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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('model_id');
            $table->decimal('currency_sum');
            $table->decimal('sum');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('counterparty_id');
            $table->unsignedBigInteger('counterparty_agreement_id');
            $table->string('type');
            $table->unsignedBigInteger('operation_type_id');
            $table->unsignedBigInteger('organization_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
