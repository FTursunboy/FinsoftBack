<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_operations', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('doc_number');
            $table->string('status');
            $table->unsignedBigInteger('sum');
            $table->boolean('active')->default(false);
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('storage_id');
            $table->unsignedInteger('author_id');
            $table->dateTime('date');
            $table->string('comment')->nullable();
            $table->unsignedInteger('currency_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_operations');
    }
};
