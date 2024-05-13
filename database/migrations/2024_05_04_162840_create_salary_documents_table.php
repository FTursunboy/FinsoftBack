<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_documents', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('doc_number');
            $table->string('date');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('author_id');
            $table->string('comment')->nullable();
            $table->boolean('active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_documents');
    }
};
