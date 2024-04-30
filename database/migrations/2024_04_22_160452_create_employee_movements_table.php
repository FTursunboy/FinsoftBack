<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_movements', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number');
            $table->string('date');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('organization_id');
            $table->float('salary');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('department_id');
            $table->string('movement_date');
            $table->string('schedule_id')->nullable();
            $table->string('basis')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_movements');
    }
};
