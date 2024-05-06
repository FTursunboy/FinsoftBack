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
        Schema::create('inventory_document_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->constrained();
            $table->integer('accounting_quantity');
            $table->integer('actual_quantity');
            $table->integer('difference');
            $table->foreignUuid('inventory_document_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_document_goods');
    }
};
