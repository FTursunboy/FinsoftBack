<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('counterparty_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contract_number');
            $table->date('date');
            $table->foreignId('organization_id')->constrained();
            $table->foreignId('counterparty_id')->constrained();
            $table->string('contact_person');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('payment_id')->nullable()->constrained('currencies');
            $table->text('comment');
            $table->foreignId('price_type_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counterparty_agreements');
    }
};
