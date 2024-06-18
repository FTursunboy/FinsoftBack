<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('doc_number');
            $table->unsignedInteger('counterparty_id');
            $table->unsignedInteger('counterparty_agreement_id');
            $table->decimal('sales_sum', 20, 3);
            $table->decimal('return_sum', 20, 3);
            $table->decimal('client_payment', 20, 3)->nullable();
            $table->boolean('active')->default(false);
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('storage_id');
            $table->unsignedInteger('author_id');
            $table->dateTime('date');
            $table->string('comment')->nullable();
            $table->unsignedInteger('currency_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
