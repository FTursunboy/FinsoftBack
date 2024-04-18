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
            $table->string('organization_id')->nullable();
            $table->string('cashRegister_id')->nullable();
            $table->string('sum')->nullable();
            $table->string('counterparty_id')->nullable();
            $table->string('counterparty_agreement_id')->nullable();
            $table->string('basis')->nullable();
            $table->string('comment')->nullable();
            $table->string('author_id')->nullable();
            $table->integer('organizationBill_id')->nullable();
            $table->integer('senderCashRegister_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('balanceKey_id')->nullable();
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
