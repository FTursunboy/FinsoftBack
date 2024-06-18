<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id');
            $table->string('service_id');
            $table->string('type');
            $table->decimal('price', 20, 3);
            $table->integer('amount');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_goods');
    }
};
