<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('firings', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number');
            $table->string('date');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('firing_date');
            $table->string('basis')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->string('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firings');
    }
};
