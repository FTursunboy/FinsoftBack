<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counterparty_settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('counterparty_id');
            $table->string('movement_type');
            $table->unsignedBigInteger('counterparty_agreement_id')->nullable();
            $table->unsignedBigInteger('organization_id');
            $table->float('sale_sum');
            $table->float('sum');
            $table->string('model_id');
            $table->boolean('active');
            $table->timestamp('date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counterparty_settlements');
    }
};
