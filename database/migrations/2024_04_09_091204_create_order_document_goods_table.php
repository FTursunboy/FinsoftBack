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
        Schema::create('order_document_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->constrained();
            $table->integer('amount');
            $table->decimal('price', 20,2);
            $table->foreignUuid('order_document_id')->constrained();
            $table->integer('auto_sale_percent')->nullable();
            $table->decimal('auto_sale_sum', 20,2)->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_document_goods');
    }
};
