<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hirings', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number');
            $table->string('data')->nullable();
            $table->unsignedBigInteger('organization_id');
            $table->integer('employee_id');
            $table->float('salary');
            $table->string('hiring_date');
            $table->string('department_id');
            $table->string('basis')->nullable();
            $table->string('position_id');
            $table->string('schedule')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hirings');
    }
};
