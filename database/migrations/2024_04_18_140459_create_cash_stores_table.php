<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_stores', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number');
            $table->string('date');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('cashRegister_id')->nullable();
            $table->string('sum')->nullable();
            $table->unsignedBigInteger('counterparty_id')->nullable();
            $table->unsignedBigInteger('counterparty_agreement_id')->nullable();
            $table->string('basis')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('organizationBill_id')->nullable();
            $table->unsignedBigInteger('senderCashRegister_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('balance_article_id')->nullable();
            $table->string('operation_type');
            $table->string('type');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_stores');
    }
};
