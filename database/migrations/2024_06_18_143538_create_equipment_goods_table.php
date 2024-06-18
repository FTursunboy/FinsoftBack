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
        Schema::create('equipment_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id');
            $table->decimal('price');
            $table->unsignedBigInteger('amount');
            $table->decimal('sum');
            $table->foreignUuid('equipment_document_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_goods');
    }
};
