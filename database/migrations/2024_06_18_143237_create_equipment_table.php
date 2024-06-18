<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->unsignedBigInteger('doc_number')->unique();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('good_id');
            $table->unsignedBigInteger('storage_id');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('author_id');
            $table->decimal('sum');
            $table->text('comment');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
