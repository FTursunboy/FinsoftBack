<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_operations', function (Blueprint $table) {
            $table->id();
            $table->string('id');
            $table->string('doc_number');
            $table->string('status_id');
            $table->string('active');
            $table->string('organization_id');
            $table->string('storage_id');
            $table->string('author_id');
            $table->dateTime('date');
            $table->string('comment');
            $table->string('currency_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_operations');
    }
};
