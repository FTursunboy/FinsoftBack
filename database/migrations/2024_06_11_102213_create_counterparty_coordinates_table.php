<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counterparty_coordinates', function (Blueprint $table) {
            $table->id();
            $table->point('location');
            $table->foreignId('counterparty_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counterparty_coordinates');
    }
};
