<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('setup_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('price_set_up_id');
            $table->unsignedInteger('good_id');
            $table->unsignedInteger('price_type_id');
            $table->decimal('old_price', 20, 3)->nullable();
            $table->decimal('new_price', 20, 3)->nullable();
            $table->string('price_set_up_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('good_types');
    }
};
