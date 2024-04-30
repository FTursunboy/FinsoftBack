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
        Schema::create('checking_accounts', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('doc_number');
            $table->string('date');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('sum')->nullable();
            $table->unsignedBigInteger('counterparty_id')->nullable();
            $table->unsignedBigInteger('counterparty_agreement_id')->nullable();
            $table->string('basis')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('organization_bill_id')->nullable();
            $table->unsignedBigInteger('sender_cash_register_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('balance_article_id')->nullable();
            $table->unsignedBigInteger('operation_type_id');
            $table->string('type');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checking_accounts');
    }
};
