<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedule_week_hours', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('week');
            $table->integer('hours');
            $table->unsignedBigInteger('schedule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_week_hours');
    }
};
