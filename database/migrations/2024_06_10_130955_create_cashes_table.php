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
        Schema::create('cashes', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->unsignedBigInteger('operation_type_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('model_id');
            $table->string('model_type');
            $table->decimal('sum');
            $table->decimal('currency_sum');
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashes');
    }
};
