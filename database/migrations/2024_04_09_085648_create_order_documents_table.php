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
        Schema::create('order_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('doc_number')->unique();
            $table->date('date');
            $table->foreignId('counterparty_id')->constrained();
            $table->foreignId('counterparty_agreement_id')->constrained();
            $table->foreignId('organization_id')->constrained();
            $table->date('shipping_date')->nullable();
            $table->foreignId('order_status_id')->nullable()->constrained();
            $table->foreignId('author_id')->constrained('users');
            $table->text('comment')->nullable();
            $table->foreignId('currency_id')->constrained();
            $table->decimal('summa', 20, 2);
            $table->foreignId('order_type_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_documents');
    }
};
