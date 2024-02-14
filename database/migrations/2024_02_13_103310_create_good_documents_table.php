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
        Schema::create('good_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_id')->constrained();
            $table->integer('amount');
            $table->decimal('price',10,2);
            $table->foreignId('document_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_documents');
    }
};
