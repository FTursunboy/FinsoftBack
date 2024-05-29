<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_document_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->float('oklad');
            $table->integer('worked_hours');
            $table->float('salary');
            $table->float('another_payments');
            $table->float('takes_from_salary');
            $table->string('payed_salary');
            $table->string('salary_document_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_document_employees');
    }
};
