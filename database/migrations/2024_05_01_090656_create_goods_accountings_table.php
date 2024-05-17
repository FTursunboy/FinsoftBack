<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('good_accountings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('storage_id');
            $table->unsignedBigInteger('good_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('movement_type');
            $table->integer('amount');
            $table->float('sum');
            $table->string('model_id');
            $table->timestamp('date');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('good_accountings');
    }
};
