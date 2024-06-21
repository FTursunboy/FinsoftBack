<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('price_set_ups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('doc_number');
            $table->string('start_date');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('author_id');
            $table->text('comment')->nullable();
            $table->string('basis');
            $table->boolean('active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_set_ups');
    }
};
